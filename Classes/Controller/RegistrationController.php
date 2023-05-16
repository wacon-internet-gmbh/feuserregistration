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
use Wacon\Feuserregistration\Service\DoubleOptinService;
use Wacon\Feuserregistration\Utility\Typo3\SiteUtility;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use Wacon\Feuserregistration\Utility\PasswordUtility;

class RegistrationController extends BaseActionController {
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create an AdvertisingCooperationTableController
     * @param \Wacon\Feuserregistration\Domain\Repository\UserRepository
     */
    public function __construct(
        \Wacon\Feuserregistration\Domain\Repository\UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
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
        // We don ask for username and password and we dont need it
        // but we want to set something, because they are required in typo3
        $newUser->setUsername($newUser->getEmail());
        $newUser->setRandomPassword();
        $newUser->setDisable(true);

        // send double opt in mail
        try {
            $service = GeneralUtility::makeInstance(DoubleOptinService::class);
            $service->setSettings($this->settings);
            $doiHash = $service->sendMail($newUser);
            $this->view->assign('mailResponse', $service->getResponse());
        
            // Create frontend user as hidden and without fe_group
            // We save the doi hash in user db
            $newUser->setDoiHash($doiHash);
            $this->userRepository->add($newUser);
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
        // We need to check, if user already exists
        // that is possible, if user has not the 
        // given feGroup and/or he is disabled
        // @TODO

        // We don ask for username and password and we dont need it
        // but we want to set something, because they are required in typo3
        $newUser->setUsername($newUser->getEmail());
        $newUser->setRandomPassword();
        $newUser->setDisable(true);

        // send double opt in mail
        try {
            $service = GeneralUtility::makeInstance(DoubleOptinService::class);
            $service->setSettings($this->settings);
            $doiHash = $service->sendMail($newUser);
            $this->view->assign('mailResponse', $service->getResponse());
        
            // Create frontend user as hidden and without fe_group
            // We save the doi hash in user db
            $newUser->setDoiHash($doiHash);
            $this->userRepository->add($newUser);
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

            try {
                $service = GeneralUtility::makeInstance(DoubleOptinService::class);
                $service->setSettings($this->settings);
                $service->sendCredentials($user, $password);
            }catch(\Exception $e) {
                $this->view->assign('error', $e->getMessage());
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