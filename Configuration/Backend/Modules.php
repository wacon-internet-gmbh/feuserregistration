<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Wacon\Feuserregistration\Controller\BackendImportController;

if (ExtensionManagementUtility::isLoaded('luxletter') === false) {
    return [];
}

return [
    'admin_feuserregistration_import' => [
        'parent' => 'tools',
        'position' => ['bottom'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/feuserregistration/import/feuser-luxletter',
        'labels' => 'LLL:EXT:feuserregistration/Resources/Private/Language/ImportFeUserForLuxletter/locallang_mod.xlf',
        'iconIdentifier' => 'tx_feuserregistration',
        'navigationComponentId' => '@typo3/backend/tree/page-tree-element',
        'extensionName' => 'Feuserregistration',
        'controllerActions' => [
            BackendImportController::class => [
                'importFeUserForLuxletter',
                'uploadFeUserForLuxletter'
            ],
        ],
    ],
];
