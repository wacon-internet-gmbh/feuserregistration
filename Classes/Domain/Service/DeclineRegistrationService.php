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
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Feuserregistration\Domain\Model\User;
use Wacon\Feuserregistration\Utility\Typo3\SiteUtility;

class DeclineRegistrationService extends DoubleOptinService
{
    /**
     * Summary of sendDeclinedInfo
     * @param User $user
     * @return void
     */
    public function sendDeclinedInfo(User $user) {
        $this->user = $user;

        $from = MailUtility::getSystemFrom();
        $fromAddress = null;

        if (!array_key_exists(0, $from)) {
            $fromAddress = new Address(current(array_keys($from)), current($from));
        } else {
            $fromAddress = new Address(current($from));
        }

        $bodytext = '';

        $bodytext = $this->getBodyHtmlForDeclined();

        $this->response = $this->mail
            ->from($fromAddress)
            ->to(
                new Address($this->user->getEmail())
            )
            ->subject(LocalizationUtility::translate('admin.activateform.declined.mail.subject', $this->extensionName, [SiteUtility::getDomain()]))
            ->html($bodytext)
            ->send();
    }

    /**
     * Summary of getBodyHtmlForDeclined
     * @return string
     */
    protected function getBodyHtmlForDeclined(): string
    {
        $html = '<p>' . LocalizationUtility::translate('admin.activateform.declined.salutation', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('admin.activateform.declined.text.1', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings', $this->extensionName) . '</p>';
        $html .= '<p>' . LocalizationUtility::translate('register.mail.doi.text.greetings.brand', $this->extensionName) . '</p>';

        return $html;
    }
}
