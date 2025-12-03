<?php

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
    $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Feuserregistration',
        'Subscribe',
        'Feuserregistration: Display a email registration form'
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pages',
        $pluginSignature,
        'after:palette:headers'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Feuserregistration',
        'Register',
        'Feuserregistration: Display a registration form'
    );

    if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 14) {
        $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Feuserregistration',
            'Verify',
            'Feuserregistration: Verify a registration.',
            null,
            'plugins',
            '',
            'FILE:EXT:' . $extensionName . '/Configuration/Flexforms/Verify.xml'
        );

        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'pages',
            $pluginSignature,
            'after:pi_flexform'
        );
    } else {
        $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Feuserregistration',
            'Verify',
            'Feuserregistration: Verify a registration.',
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            'FILE:EXT:' . $extensionName . '/Configuration/Flexforms/Verify.xml'
        );
    }
     /******************************************************************
     * FRONTEND PLUGINS - END
     *****************************************************************/
})();
