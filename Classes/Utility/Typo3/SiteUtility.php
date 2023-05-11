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

 namespace Wacon\Feuserregistration\Utility\Typo3;

 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use \Psr\Http\Message\ServerRequestInterface;

 class SiteUtility {
    /**
     * Return the current domain
     * @return string
     */
    public static function getDomain() {
        $base = $GLOBALS['TYPO3_REQUEST']->getAttribute('site')->getBase();
        
        return $base->getAuthority();
    }

    /**
     * Return all available languages for f:form.select
     * @param ServerRequestInterface $request
     * @return array
     */
    public static function getAllLanguagesForSelect(ServerRequestInterface $request = null) {
        if (!$request) {
            $request = $GLOBALS['TYPO3_REQUEST'];
        }

        $site = $request->getAttribute('site');
        $siteLanguages = $site->getLanguages();
        $options = [];

        foreach($siteLanguages as $siteLanguage) {
            $options[$siteLanguage->getLanguageId()] = $siteLanguage->getNavigationTitle() ? $siteLanguage->getNavigationTitle() : $siteLanguage->getTitle();
        }

        return $options;
    }
 }