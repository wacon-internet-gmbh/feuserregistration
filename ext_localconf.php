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
        'Subscribe',
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'formEmail, registerEmail'
        ],
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'formEmail, registerEmail'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Feuserregistration',
        'Register',
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'form, register'
        ],
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'form, register'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Feuserregistration',
        'Verify',
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'doi'
        ],
        [
            \Wacon\Feuserregistration\Controller\RegistrationController::class => 'doi'
        ]
    );
    /******************************************************************
     * FRONTEND PLUGINS - END
     *****************************************************************/
})();