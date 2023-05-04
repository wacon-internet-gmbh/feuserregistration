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

 namespace Wacon\Feuserregistration\Service;
 
 use Wacon\Feuserregistration\Domain\Model\User;
 use Symfony\Component\Mime\Address;
 use TYPO3\CMS\Core\Mail\MailMessage;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
 use TYPO3\CMS\Core\Utility\MailUtility;
 use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
 use Wacon\Feuserregistration\Utility\SiteUtility;
 use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
 use TYPO3\CMS\Extbase\Mvc\Request;
 use TYPO3\CMS\Extbase\Mvc\RequestInterface;
 
 class DoubleOptinService {
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
     * Create a DoubleOptinService
     * @param MailMessage $mail
     * @return void
     */
    public function __construct(MailMessage $mail) {
        $this->mail = $mail;
    }

    /**
     * Send the DOI Mail
     * @param User $user
     * @return string
     */
    public function sendMail(User $user) {
        $this->hash = md5(uniqid($user->getEmail()));
        $this->user = $user;
        
        $from = MailUtility::getSystemFrom();
        $fromAddress = null;

        if (!array_key_exists(0, $from)) {
            $fromAddress = new Address(current(array_keys($from)), current($from));
        }else {
            $fromAddress = new Address(current($from));
        }

        $this->response = $this->mail
            ->from($fromAddress)
            ->to(
                new Address($this->user->getEmail())
            )        
            ->subject(LocalizationUtility::translate('register.mail.doi.subject', $this->extensionName, [SiteUtility::getDomain()]))
            ->html($this->getBodyHtml())
            ->send();

        return $this->hash;
    }

    /**
     * Return the last mail response
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Build the verification link
     * @return string
     */
    protected function buildVerificationUri() {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriBuilder->setRequest($this->getExtbaseRequest());

        return $uriBuilder
            ->reset()
            ->setTargetPageUid($GLOBALS['TSFE']->id)
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                'doi',
                [
                    'doihash' => $this->hash
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
    protected function getBodyHtml() {
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
 }