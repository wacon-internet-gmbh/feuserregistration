<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
// all use statements must come first
//use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Prevent Script from being called directly
defined('TYPO3') or die();

// encapsulate all locally defined variables
(function () {
    $extensionName = 'feuserregistration';

    /******************************************************************
     * FRONTEND PLUGINS - START
     *****************************************************************/
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Feuserregistration',
        'Subscribe',
        'Feuserregistration: Display a email registration form'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Feuserregistration',
        'Register',
        'Feuserregistration: Display a registration form'
    );

    $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Feuserregistration',
        'Verify',
        'Feuserregistration: Verify a registration.'
    );

    $xmlFile = 'FILE:EXT:' . $extensionName . '/Configuration/Flexforms/Verify.xml';

    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);

    if (version_compare($versionInformation->getVersion(), '12.0', '<')) {
        $pluginSignature = $extensionName . '_verify';
        $xmlFile = 'FILE:EXT:' . $extensionName . '/Configuration/Flexforms/VerifyV11.xml';
    }

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        $xmlFile
    );
     /******************************************************************
     * FRONTEND PLUGINS - END
     *****************************************************************/
})();
