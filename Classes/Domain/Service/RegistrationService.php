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

 namespace Wacon\Feuserregistration\Domain\Service;

 use Psr\Http\Message\ServerRequestInterface;
 use Wacon\Feuserregistration\Domain\Repository\UserRepository;
 use Wacon\Feuserregistration\Domain\Exception\DoiNotSendException;
 use Wacon\Feuserregistration\Domain\Model\User;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use Wacon\Feuserregistration\Utility\Typo3\Extbase\PersistenceUtility;

 class RegistrationService
 {
    protected bool $mailResponseForDOI = false;

    public function __construct(
        protected readonly UserRepository $userRepository
    ) {}

    /**
     * Register a user by email and returns the frontend_user uid
     * @param string $email
     * @param int $pid
     * @param array $settings
     * @param ServerRequestInterface $request
     * @param bool $privacy
     * @return User
     * @throws DoiNotSendException
     */
    public function registerSimple(string $email, int $pid, array $settings, ServerRequestInterface $request, bool $privacy = true): User {
        // We need to check, if user already exists
        // that is possible, if user has not the
        // given feGroup and/or he is disabled
        PersistenceUtility::removeAllRestrictions($this->userRepository, ['disabled', 'fe_group']);

        // Make sure pid is inside the StoragePages
        PersistenceUtility::addStoragePageUids($this->userRepository, [$pid]);

        $user = $this->userRepository->findByEmail($email)->current();

        // if user does not exist, then create a new one
        if (!$user) {
            $user = GeneralUtility::makeInstance(User::class);
            $user->setEmail($email);
        }

        // We don ask for username and password and we dont need it
        // but we want to set something, because they are required in typo3
        $user->setUsername($email);
        $user->setRandomPassword();
        $user->setDisable(true);
        $user->setPid($pid);

        if ($privacy) {
            $user->setPrivacy($privacy);
        }

        // send double opt in mail
        try {
            $service = GeneralUtility::makeInstance(DoubleOptinService::class, $request);
            $service->setSettings($settings);
            $doiHash = $service->sendMail($user);
            $this->mailResponseForDOI = $service->getResponse();

            // Create frontend user as hidden and without fe_group
            // We save the doi hash in user db
            $user->setDoiHash($doiHash);

            if ($user->_isNew()) {
                $this->userRepository->add($user);
            }else {
                $this->userRepository->update($user);
            }
        }catch(\Exception $e) {
            throw new DoiNotSendException('Error during DOI process of feuserregistration. Prior Message: ' . $e->getMessage(), time(), $e);
        }

        return $user;
    }

    /**
     * Register a user and returns the frontend_user uid
     * @param User $user
     * @param int $pid
     * @param array $settings
     * @param ServerRequestInterface $request
     * @param bool $privacy
     * @return User
     * @throws DoiNotSendException
     */
    public function register(User $user, int $pid, array $settings, ServerRequestInterface $request, bool $privacy = true): User {
        // We need to check, if user already exists
        // that is possible, if user has not the
        // given feGroup and/or he is disabled
        PersistenceUtility::removeAllRestrictions($this->userRepository, ['disabled', 'fe_group']);

        // Make sure pid is inside the StoragePages
        PersistenceUtility::addStoragePageUids($this->userRepository, [$pid]);

        // We don ask for username and password and we dont need it
        // but we want to set something, because they are required in typo3
        $user->setUsername($user->getEmail());
        $user->setRandomPassword();
        $user->setDisable(true);
        $user->setPid($pid);

        if ($privacy) {
            $user->setPrivacy($privacy);
        }

        // send double opt in mail
        try {
            $service = GeneralUtility::makeInstance(DoubleOptinService::class, $request);
            $service->setSettings($settings);
            $doiHash = $service->sendMail($user);
            $this->mailResponseForDOI = $service->getResponse();

            // Create frontend user as hidden and without fe_group
            // We save the doi hash in user db
            $user->setDoiHash($doiHash);

            if ($user->_isNew()) {
                $this->userRepository->add($user);
            }else {
                $this->userRepository->update($user);
            }
        }catch(\Exception $e) {
            throw new DoiNotSendException('Error during DOI process of feuserregistration. Prior Message: ' . $e->getMessage(), time(), $e);
        }

        return $user;
    }

    /**
     * Get the value of mailResponseForDOI
     */
    public function getMailResponseForDOI()
    {
        return $this->mailResponseForDOI;
    }

    /**
     * Set the value of mailResponseForDOI
     *
     * @return  self
     */
    public function setMailResponseForDOI($mailResponseForDOI)
    {
        $this->mailResponseForDOI = $mailResponseForDOI;

        return $this;
    }
 }
