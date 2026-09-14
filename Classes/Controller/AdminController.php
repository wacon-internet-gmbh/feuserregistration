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

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Feuserregistration\Domain\Repository\UserRepository;
use Wacon\Feuserregistration\Domain\Service\DeclineRegistrationService;
use Wacon\Feuserregistration\Domain\Service\DoubleOptinService;
use Wacon\Feuserregistration\Domain\Service\RegistrationService;
use Wacon\Feuserregistration\Utility\PasswordUtility;

class AdminController extends BaseActionController
{
    /**
     * Create an RegistrationController
     */
    public function __construct(
        protected readonly UserRepository $userRepository,
        protected readonly RegistrationService $registrationService
    ) {}

    /**
     * Show a registration form
     * @return ResponseInterface|string
     */
    public function activateFormAction(string $hash)
    {
        if (!$hash) {
            return new ForwardResponse('nothing');
        }

        $querySettings = $this->userRepository->createQuery()->getQuerySettings();
        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setEnableFieldsToBeIgnored(['disabled']);
        $this->userRepository->setDefaultQuerySettings($querySettings);
        $user = $this->userRepository->findByDoiHash($hash)->current();

        if ($user) {
            // check if backend login is active
            $validBackendUserGroups = GeneralUtility::trimExplode(',', $this->settings['adminActivation']['backendGroups'], true);
            $backendUserGroups = GeneralUtility::trimExplode(',', $GLOBALS['BE_USER']->user['usergroup'], true);
            $isInGroup = count(array_intersect($validBackendUserGroups, $backendUserGroups)) > 0;

            if ($GLOBALS['BE_USER']->isAdmin() || $isInGroup) {
                $this->view->assign('user', $user);
            } else {
                $this->view->assign('errorMessage', LocalizationUtility::translate('validation.error.notAllowed', 'feuserregistration'));
            }
        } else {
            return new ForwardResponse('nothing');
        }

        return $this->htmlResponse();
    }

    /**
     * Approve or decline registration
     * @param string $submit
     * @param string $doihash
     * @return ResponseInterface
     */
    public function activateAction(string $submit, string $doihash): ResponseInterface
    {
        $querySettings = $this->userRepository->createQuery()->getQuerySettings();
        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setEnableFieldsToBeIgnored(['disabled']);
        $this->userRepository->setDefaultQuerySettings($querySettings);
        $user = $this->userRepository->findByDoiHash($doihash)->current();

        if (!$user) {
            throw new InvalidArgumentException();
        }

        if ($submit === LocalizationUtility::translate('admin.activateform.decline', 'feuserregistration')) {
            $service = GeneralUtility::makeInstance(DeclineRegistrationService::class, $this->request);
            $service->sendDeclinedInfo($user);
            $this->userRepository->remove($user);
            $this->view->assign('message', LocalizationUtility::translate('admin.activateform.declined', 'feuserregistration'));
        } else {
            $user->setDisable(false);
            $user->setDoiHash('');
            $user->addFeGroup($this->settings['fegroups']['target']);
            $password = PasswordUtility::random();
            $user->setPassword(PasswordUtility::hashPassword($password));
            $this->userRepository->update($user);

            // if login page is set, then send credentials to user
            if ($this->settings['pages']['loginPage']) {
                try {
                    $service = GeneralUtility::makeInstance(DoubleOptinService::class, $this->request);
                    $service->setSettings($this->settings);
                    $service->sendCredentials($user, $password);
                } catch (\Exception $e) {
                    $this->view->assign('error', $e->getMessage());
                }

                $this->view->assign('message', LocalizationUtility::translate('register.form.text.afterDoi', 'feuserregistration'));
            }

            $this->view->assign('message', LocalizationUtility::translate('admin.activateform.approved', 'feuserregistration'));
        }

        return $this->htmlResponse();
    }

    /**
     * Action to show nothing
     * @return ResponseInterface
     */
    public function nothingAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}
