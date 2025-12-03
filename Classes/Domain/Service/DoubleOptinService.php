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
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Feuserregistration\Domain\Model\User;
use Wacon\Feuserregistration\Utility\Typo3\SiteUtility;

class DoubleOptinService
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var MailMessage
     */
    protected $mail;

    /**
     * @var string
     */
    protected $extensionName = 'feuserregistration';

    /**
     * @var mixed
     */
    protected $response;

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
     * Send the DOI Mail
     * @param User $user
     * @return string
     */
    public function sendMail(User $user): string
    {
        $this->hash = md5(uniqid($user->getEmail()));
        $this->user = $user;

        $from = MailUtility::getSystemFrom();
        $fromAddress = null;

        if (!array_key_exists(0, $from)) {
            $fromAddress = new Address(current(array_keys($from)), current($from));
        } else {
            $fromAddress = new Address(current($from));
        }

        $this->mail
            ->from($fromAddress)
            ->to(
                new Address($this->user->getEmail())
            )
            ->subject(LocalizationUtility::translate('register.mail.doi.subject', $this->extensionName, [SiteUtility::getDomain()]))
            ->html($this->getBodyHtml());

        GeneralUtility::makeInstance(MailerInterface::class)->send($this->mail);
        $this->response = true;

        return $this->hash;
    }

    /**
     * Send the Credentials to user
     * @param User $user
     * @param string $password
     * @return string
     */
    public function sendCredentials(User $user, string $password): string
    {
        $this->user = $user;

        $from = MailUtility::getSystemFrom();
        $fromAddress = null;

        if (!array_key_exists(0, $from)) {
            $fromAddress = new Address(current(array_keys($from)), current($from));
        } else {
            $fromAddress = new Address(current($from));
        }

        $bodytext = '';

        if ($this->hasLoginPage()) {
            $bodytext = $this->getBodyHtmlForCredentials($user, $password);
        } else {
            $bodytext = $this->getBodyHtmlForNonCredentials($user);
        }

        $this->mail
            ->from($fromAddress)
            ->to(
                new Address($this->user->getEmail())
            )
            ->subject(LocalizationUtility::translate('register.mail.doi.credentials.subject', $this->extensionName, [SiteUtility::getDomain()]))
            ->html($bodytext);

        GeneralUtility::makeInstance(MailerInterface::class)->send($this->mail);

        return $this->hash;
    }

    /**
     * Return the last mail response
     * @return mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * Build the verification link
     * @return string
     */
    protected function buildLoginUri(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriBuilder->setRequest($this->getExtbaseRequest());

        return $uriBuilder
            ->reset()
            ->setTargetPageUid($this->getLoginPageUid())
            ->setCreateAbsoluteUri(true)
            ->setRequest($this->request)
            ->build();
    }

    /**
     * Build the verification link
     * @return string
     */
    protected function buildVerificationUri(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriBuilder->setRequest($this->getExtbaseRequest());

        return $uriBuilder
            ->reset()
            ->setTargetPageUid($this->getVerificationPageUid())
            ->setCreateAbsoluteUri(true)
            ->setRequest($this->request)
            ->uriFor(
                'doi',
                [
                    'doihash' => $this->hash,
                ],
                'Registration',
                'feuserregistration',
                'verify'
            );
    }

    /**
     * Return the body text
     * @TODO Use StandaloneView or FluidMail
     * @return string
     */
    protected function getBodyHtml(): string
    {
        $uri = $this->buildVerificationUri();

        $html = '<p>' . LocalizationUtility::translate('register.mail.doi.text.salutation', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.1', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.2', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.3', $this->extensionName) . '</p>';
        $html .= '<p><a href="' . $uri . '">' . $uri . '</a><br />';
        $html .= LocalizationUtility::translate('register.mail.doi.link.helpText', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings.brand', $this->extensionName) . '</p>';

        return $html;
    }

    /**
     * Return the body text
     * @TODO Use StandaloneView or FluidMail
     * @param User $user
     * @param string $password
     * @return string
     */
    protected function getBodyHtmlForCredentials(User $user, $password): string
    {
        $uri = $this->buildLoginUri();

        $html = '<p>' . LocalizationUtility::translate('register.mail.doi.text.salutation', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.credentials.text.1', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.credentials.text.2', $this->extensionName, [$user->getUsername(), $password]) . '</p>';
        $html .= '<p><a href="' . $uri . '">' . $uri . '</a><br />';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings.brand', $this->extensionName) . '</p>';

        return $html;
    }

    /**
     * Return the body text, if no login page is set
     * @TODO Use StandaloneView or FluidMail
     * @param User $user
     * @return string
     */
    protected function getBodyHtmlForNonCredentials(User $user): string
    {
        $html = '<p>' . LocalizationUtility::translate('register.mail.doi.text.salutation', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.none_credentials.text.1', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings.brand', $this->extensionName) . '</p>';

        return $html;
    }

    /**
     * Return Extbase Request
     * @return RequestInterface
     */
    private function getExtbaseRequest(): RequestInterface
    {
        /** @var ServerRequestInterface $request */
        $request = $GLOBALS['TYPO3_REQUEST'];

        // We have to provide an Extbase request object
        return new Request(
            $request->withAttribute('extbase', new ExtbaseRequestParameters())
        );
    }

    /**
     * Get typoScript settings
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Set typoScript settings
     *
     * @param array  $settings  TypoScript settings
     *
     * @return self
     */
    public function setSettings(array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Return the page uid, where
     * the verification plugin is located
     * @return int
     */
    public function getLoginPageUid(): int
    {
        if (!empty($this->settings) && array_key_exists('pages', $this->settings) && is_array($this->settings['pages']) && array_key_exists('loginPage', $this->settings['pages']) && !empty($this->settings['pages']['loginPage'])) {
            return (int)$this->settings['pages']['loginPage'];
        }

        return 0;
    }

    /**
     * Return the page uid, where
     * the verification plugin is located
     * @return int
     */
    public function getVerificationPageUid(): int
    {
        if (!empty($this->settings) && array_key_exists('pages', $this->settings) && is_array($this->settings['pages']) && array_key_exists('verificationPage', $this->settings['pages']) && !empty($this->settings['pages']['verificationPage'])) {
            return (int)$this->settings['pages']['verificationPage'];
        }

        return $GLOBALS['TSFE']->id;
    }

    /**
     * Return TRUE if login page is set in TypoScript
     * @return bool
     */
    public function hasLoginPage(): bool
    {
        return (int)$this->getLoginPageUid() > 0 ? true : false;
    }
}
