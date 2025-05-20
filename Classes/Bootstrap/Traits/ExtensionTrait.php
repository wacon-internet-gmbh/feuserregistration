<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension: feuserregistration.
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

namespace Wacon\Feuserregistration\Bootstrap\Traits;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

trait ExtensionTrait
{
    protected $extensionKey = 'feuserregistration';

    /**
     * Return the extension key in Namespace writing
     * @return string
     */
    protected function getExtensionKeyAsNamespace(): string
    {
        return GeneralUtility::underscoredToUpperCamelCase($this->extensionKey);
    }

    /**
     * Return the extension key in lower case without underscores
     * @return string
     */
    protected function getExtensionKeyForFlexforms(): string
    {
        return strtolower($this->getExtensionKeyAsNamespace());
    }

    /**
     * Return the LLL path as string
     * @param string $key
     * @return string
     */
    protected function getLLL(string $key): string
    {
        return 'LLL:EXT:' . $this->extensionKey . '/Resources/Private/Language/' . $key;
    }

    /**
     * Return LLL path for core labels
     * @param string $key
     * @return string
     */
    protected function getLLLForCoreLabels(string $key): string
    {
        return 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:' . $key;
    }

    /**
     * Return the path to FlexForm file
     * @param string $filename
     * @return string
     */
    protected function getFlexformPath(string $filename): string
    {
        return 'FILE:EXT:' . $this->extensionKey . '/Configuration/FlexForm/' . $filename;
    }

    /**
     * Return the path for TsConfig files
     * @param string $filename
     * @param string $scope (Page or User)
     * @return string
     */
    protected function getTsConfigPath(string $filename, string $scope = 'Page'): string
    {
        return 'Configuration/TSconfig/' . $scope . '/StaticFile/' . $filename;
    }

    /**
     * Return path for Icons
     * @param string $fileName
     * @return string
     */
    protected function getIconPath(string $fileName): string
    {
        return 'EXT:' . $this->extensionKey . '/Resources/Public/Icons/' . $fileName;
    }

    /**
     * Register a flexform
     * @param string $pluginSignature
     * @param string $fileName
     */
    protected function registerFlexform(string $pluginSignature, string $fileName)
    {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

        ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            $this->getFlexformPath($fileName)
        );
    }

    /**
     * Register a flexform to a CType
     * @param string $cType
     * @param string $fileName
     */
    protected function registerFlexformToCType(string $cType, string $fileName)
    {
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            $this->getFlexformPath($fileName),
            $cType
        );
    }

    /**
     * Set $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature]
     * @param string $value
     */
    protected function setSubtypesExcludelist(string $pluginSignature, string $value)
    {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = $value;
    }

    /**
     * Retunr the EXT path to Configuration inside the current extension
     * @return string
     */
    protected function getConfigPath(): string
    {
        return 'EXT:' . $this->extensionKey . '/Configuration/';
    }

    /**
     * Register a RTE preset file
     * @param string $key
     * @param string $yamlFilename
     */
    protected function registerRTEPreset(string $key, string $yamlFilename)
    {
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets'][$key] = $this->getConfigPath() . 'RTE/' . $yamlFilename;
    }

    /**
     * Return extension key without _
     */
    protected function getExtensionKeyForRedirect(): string
    {
        return str_replace('_', '', $this->extensionKey);
    }

    /**
     * Utilize \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
     * @param string $subKey
     * @return array
     */
    protected function getGlobalExtensionSettings(string $subKey = ''): array
    {
        if (!empty($subKey)) {
            return GeneralUtility::makeInstance(ExtensionConfiguration::class)
                ->get($this->extensionKey, $subKey);
        }

        return GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get($this->extensionKey);
    }
}
