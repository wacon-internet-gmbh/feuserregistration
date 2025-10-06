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

use Wacon\Feuserregistration\Controller\BackendImportController;

/**
 * Definitions for modules provided by EXT:examples
 */
return [
    'admin_examples' => [
        'parent' => 'tools',
        'position' => ['bottom'],
        'access' => 'admin',
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
