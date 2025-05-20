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

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Feuserregistration\Bootstrap\Traits\ExtensionTrait;
use Wacon\Feuserregistration\Domain\Model\User;
use Wacon\Feuserregistration\Utility\Typo3\SiteUtility;

class AdminInfoMailService
{
    use ExtensionTrait;
    public static string $MODE_VERIFICATION = 'verification';
    public static string $MODE_REGISTRATION = 'registration';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var MailMessage
     */
    protected $mail;

    /**
     * TypoScript settings
     * @var array
     */
    protected $settings;

    /**
     * Current Request object
     * @var ServerRequestInterface
     */
    protected ServerRequestInterface $request;

    /**
     * Create a DoubleOptinService
     * @param MailMessage $mail
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->mail = GeneralUtility::makeInstance(MailMessage::class);
        $this->request = $request;
    }

    /**
     * Send the info mail to admin
     * @param User $user
     * @return bool
     */
    public function sendRegistrationMail(User $user): bool
    {
        $from = MailUtility::getSystemFrom();
        $fromAddress = null;

        if (!array_key_exists(0, $from)) {
            $fromAddress = new Address(current(array_keys($from)), current($from));
        } else {
            $fromAddress = new Address(current($from));
        }

        $this->mail->from($fromAddress);

        $receivers = GeneralUtility::trimExplode(',', $this->settings['mails']['onRegistration']['receivers'], true);

        if (count($receivers) == 0) {
            return false;
        }

        foreach ($receivers as $key => $receiver) {
            if ($key == 0) {
                $this->mail->to(new Address($receiver));
            } else {
                $this->mail->addCc(new Address($receiver));
            }
        }

        return $this->mail
            ->subject(LocalizationUtility::translate('register.mail.adminInfo.subject.' . self::$MODE_REGISTRATION, $this->extensionKey, [SiteUtility::getDomain()]))
            ->html($this->getBodyHtmlForAdminInfo(self::$MODE_REGISTRATION, $user, SiteUtility::getDomain(), SiteUtility::getBaseUrl()))
            ->send();
    }

    /**
     * Send the info mail to admin on verification
     * @param User $user
     * @return bool
     */
    public function sendVerificationMail(User $user): bool
    {
        $from = MailUtility::getSystemFrom();
        $fromAddress = null;

        if (!array_key_exists(0, $from)) {
            $fromAddress = new Address(current(array_keys($from)), current($from));
        } else {
            $fromAddress = new Address(current($from));
        }

        $this->mail->from($fromAddress);

        $receivers = GeneralUtility::trimExplode(',', $this->settings['mails']['onVerification']['receivers'], true);

        if (count($receivers) == 0) {
            return false;
        }

        foreach ($receivers as $key => $receiver) {
            if ($key == 0) {
                $this->mail->to(new Address($receiver));
            } else {
                $this->mail->addCc(new Address($receiver));
            }
        }

        return $this->mail
            ->subject(LocalizationUtility::translate('register.mail.adminInfo.subject.' . self::$MODE_VERIFICATION, $this->extensionKey, [SiteUtility::getDomain()]))
            ->html($this->getBodyHtmlForAdminInfo(self::$MODE_VERIFICATION, $user, SiteUtility::getDomain(), SiteUtility::getBaseUrl()))
            ->send();
    }

    /**
     * Return mail body for admin info mail
     * @param string $mode
     * @param User $user
     * @param string $domain
     * @param string $baseUrl
     * @return string
     */
    private function getBodyHtmlForAdminInfo(string $mode, User $user, string $domain, string $baseUrl): string
    {
        $html = '<p>' . LocalizationUtility::translate('register.mail.adminInfo.text.salutation', $this->extensionKey) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.adminInfo.text.' . $mode, $this->extensionKey, [$user->getUsername(), $domain]) . '</p>';
        $html .= '<p><a href="' . $baseUrl . '">' . $domain . '</a><br />';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.adminInfo.text.greetings', $this->extensionKey) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.adminInfo.text.greetings.brand', $this->extensionKey) . '</p>';

        return $html;
    }

    /**
     * Get typoScript settings
     *
     * @return  array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set typoScript settings
     *
     * @param  array  $settings  TypoScript settings
     *
     * @return  self
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }
}
