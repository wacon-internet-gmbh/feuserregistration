<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension feuserregistration.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */

namespace Wacon\Feuserregistration\Controller;

use Psr\Http\Message\ResponseInterface;
use Wacon\Feuserregistration\Domain\Model\User;
use Wacon\Feuserregistration\Domain\Repository\UserRepository;
use Wacon\Feuserregistration\Domain\Service\DoubleOptinService;
use Wacon\Feuserregistration\Utility\Typo3\SiteUtility;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use Wacon\Feuserregistration\Utility\PasswordUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Feuserregistration\Domain\Service\RegistrationService;

class RegistrationController extends BaseActionController {
    /**
     * Create an RegistrationController
     */
    public function __construct(
        protected readonly UserRepository $userRepository,
        protected readonly RegistrationService $registrationService
    ) {
        
    }

    /**
     * Show a registration form
     * @return ResponseInterface|string
     */
    public function formAction() {
        $languages = SiteUtility::getAllLanguagesForSelect($this->request);
        $this->view->assign('languages', $languages);
        return $this->htmlResponse();
    }

    /**
     * Show a registration form with just email field.
     * Mainly used for NL subscriptions
     * @return ResponseInterface|string
     */
    public function formEmailAction() {

        $languages = SiteUtility::getAllLanguagesForSelect($this->request);
        $this->view->assign('languages', $languages);
        return $this->htmlResponse();
    }

    /**
     * Execute the Registration process
     * @param User $newUser
     * @return ResponseInterface|string
     * @Validate(param="newUser", validator="Wacon\Feuserregistration\Domain\Validator\RegisterValidator")
     */
    public function registerAction(User $newUser) {        
        try {
            // Register with DOI process
            $newUser = $this->registrationService->registerSimple($newUser->getEmail(), current(GeneralUtility::intExplode(',', $this->configurationManager->getContentObject()->data['pages'], true)), $this->settings);
            $this->view->assign('mailResponse', $this->registrationService->getMailResponseForDOI());
        }catch(\Exception $e) {
            $this->view->assign('error', $e->getMessage());
        }

        $this->view->assign('newUser', $newUser);
        return $this->htmlResponse();
    }

    /**
     * Execute the Registration process
     * @param User $newUser
     * @return ResponseInterface|string
     * @Validate(param="newUser", validator="Wacon\Feuserregistration\Domain\Validator\RegisterEmailValidator")
     */
    public function registerEmailAction(User $newUser) {
        try {
            // Register with DOI process
            $newUser = $this->registrationService->registerSimple($newUser->getEmail(), current(GeneralUtility::intExplode(',', $this->configurationManager->getContentObject()->data['pages'], true)), $this->settings);
            $this->view->assign('mailResponse', $this->registrationService->getMailResponseForDOI());
        }catch(\Exception $e) {
            $this->view->assign('error', $e->getMessage());
        }

        $this->view->assign('newUser', $newUser);
        return $this->htmlResponse();
    }

    /**
     * Validates the user through double opt in
     * @return ResponseInterface|string
     */
    public function doiAction() {
        if (!$this->request->hasArgument('doihash')) {
            return new ForwardResponse('nothing');
        }
        
        $querySettings = $this->userRepository->createQuery()->getQuerySettings();
        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setEnableFieldsToBeIgnored(['disabled']);
        $this->userRepository->setDefaultQuerySettings($querySettings);
        $user = $this->userRepository->findByDoiHash($this->request->getArgument('doihash'))->current();
        
        if ($user) {
            $user->setDisable(false);
            $user->setDoiHash('');
            $user->addFeGroup($this->settings['fegroups']['target']);
            $password = PasswordUtility::random();
            $user->setPassword(PasswordUtility::hashPassword($password));
            $this->userRepository->update($user);

            // if login page is set, then send credentials to user
            if ($this->settings['pages']['loginPage']) {
                try {
                    $service = GeneralUtility::makeInstance(DoubleOptinService::class);
                    $service->setSettings($this->settings);
                    $service->sendCredentials($user, $password);
                }catch(\Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }

                $this->view->assign('message', LocalizationUtility::translate('register.form.text.afterDoi', 'feuserregistration'));
            }else {
                $this->view->assign('message', LocalizationUtility::translate('register.form.text.afterDoi.noCredentials', 'feuserregistration'));
            }            
        }

        $this->view->assign('user', $user);

        return $this->htmlResponse();
    }

    /**
     * Action to show nothing
     * @return ResponseInterface|string
     */
    public function nothingAction() {
        return $this->htmlResponse();
    }
}