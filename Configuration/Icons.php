<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    // Icon identifier
    'tx_feuserregistration' => [
        // Icon provider class
        'provider' => SvgIconProvider::class,
        // The source SVG for the SvgIconProvider
        'source' => 'EXT:feuserregistration/Resources/Public/Icons/Extension.svg',
    ],
];
