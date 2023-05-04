<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension t3templates_base.
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
use TYPO3\CMS\Extbase\Annotation\Validate;
use Wacon\Feuserregistration\Service\DoubleOptinService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * Show a simple registration form
     * @return ResponseInterface|string
     */
    public function formAction() {
        

        return $this->htmlResponse();
    }

    /**
     * Execute the Registration process
     * @param User $newUser
     * @return ResponseInterface|string
     * @Validate(param="newUser", validator="Wacon\Feuserregistration\Domain\Validator\UserValidator")
     */
    public function registerAction(User $newUser) {
        // We don ask for username and password and we dont need it
        // but we want to set something, because they are required in typo3
        $newUser->setUsername($newUser->getEmail());
        $newUser->setRandomPassword();

        // send double opt in mail
        $service = GeneralUtility::makeInstance(DoubleOptinService::class);
        $doiHash = $service->sendMail($newUser);

        // Create frontend user as hidden and without fe_group
        // We save the doi hash in user db
        $newUser->setDoiHash($doiHash);
        $this->userRepository->add($newUser);

        $this->view->assign('newUser', $newUser);
        return $this->htmlResponse();
    }

    /**
     * Validates the user through double opt in
     * @return ResponseInterface|string
     */
    public function doiAction() {
        
        return $this->htmlResponse();
    }
}