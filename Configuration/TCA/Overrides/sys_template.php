<?php
// all use statements must come first
//use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Prevent Script from being called directly
defined('TYPO3') or die();

// encapsulate all locally defined variables
(function () {
    $extensionName = 'feuserregistration';

    /******************************************************************
     * TYPOSCRIPT - START
     *****************************************************************/
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionName,
        'Configuration/TypoScript',
        'Frontend User Registration - Base'
    );
    /******************************************************************
     * TYPOSCRIPT - END
     *****************************************************************/
})();