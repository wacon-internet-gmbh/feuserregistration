<?php
// all use statements must come first
//use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Prevent Script from being called directly
defined('TYPO3') or die();

// encapsulate all locally defined variables
(function () {
    $extensionName = 'feuserregistration';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users',
        [
            'doi_hash' =>[
                'config' => [
                    'type' => 'input',
                    'readOnly' => true,
                ],
                'exclude' => 1,                
                'label' => 'LLL:EXT:feuserregistration/Resources/Private/Language/locallang_tca.xlf:fe_users.doi_hash'
            ],
            'privacy' =>[
                'config' => [
                    'type' => 'check',
                    'readOnly' => true,
                ],
                'exclude' => 1,                
                'label' => 'LLL:EXT:feuserregistration/Resources/Private/Language/locallang_tca.xlf:fe_users.privacy'
            ],
            'salutation' =>[
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        [
                            'label' => '',
                            'value' => '',
                        ],
                        [
                            'label' => 'LLL:EXT:feuserregistration/Resources/Private/Language/locallang.xlf:register.form.salutation.I.female',
                            'value' => 'female',
                        ],
                        [
                            'label' => 'LLL:EXT:feuserregistration/Resources/Private/Language/locallang.xlf:register.form.salutation.I.male',
                            'value' => 'male',
                        ],
                        [
                            'label' => 'LLL:EXT:feuserregistration/Resources/Private/Language/locallang.xlf:register.form.salutation.I.others',
                            'value' => 'others',
                        ],
                    ],
                ],
                'exclude' => 1,                
                'label' => 'LLL:EXT:feuserregistration/Resources/Private/Language/locallang.xlf:register.form.salutation'
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        'doi_hash,privacy',
        '',
        'after:lastlogin'
     );

     \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        'salutation',
        '',
        'after:title'
     );
})();