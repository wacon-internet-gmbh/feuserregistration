<?php
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

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:' . $extensionName . '/Configuration/Flexforms/Verify.xml'
    );
     /******************************************************************
     * FRONTEND PLUGINS - END
     *****************************************************************/
})();