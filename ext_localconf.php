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
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Feuserregistration',
        'Register',
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'form, register,doi'
        ],
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'form, register,doi'
        ]
    );
    /******************************************************************
     * FRONTEND PLUGINS - END
     *****************************************************************/
})();