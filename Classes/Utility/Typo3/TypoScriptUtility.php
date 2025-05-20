<?php

declare(strict_types=1);

namespace Wacon\Feuserregistration\Utility\Typo3;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/*
 * This file is part of the TYPO3 extension t3templates_base.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
class TypoScriptUtility implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Converts given array to TypoScript
     *
     * @param array $typoScriptArray The array to convert to string
     * @param string $addKey Prefix given values with given key (eg. lib.whatever = {...})
     * @param integer $tab Internal
     * @param boolean $init Internal
     * @return string TypoScript
     */
    static public function convertArrayToTypoScript(array $typoScriptArray, $addKey = '', $tab = 0, $init = true) {
        $typoScript = '';
        if ($addKey !== '') {
            $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . $addKey . " {\n";
            if ($init === true) {
                $tab++;
            }
        }
        $tab++;
        foreach ($typoScriptArray as $key => $value) {
            if (!is_array($value)) {
                if (strpos($value, "\n") === false) {
                    $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . "$key = $value\n";
                } else {
                    $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . "$key (\n$value\n" . str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . ")\n";
                }
            } else {
                $typoScript .= self::convertArrayToTypoScript($value, $key, $tab, false);
            }
        }
        if ($addKey !== '') {
            $tab--;
            $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . '}';
            if ($init !== true) {
                $typoScript .= "\n";
            }
        }
        return $typoScript;
    }

    /**
     * Return TypoScript from any extension
     * @param string $subPath
     * @return string|array|null
     */
    public static function getTypoScript(string $subPath) {
        $configurationManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
        $typoscript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $typoscriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TypoScriptService::class);

        $subPathToFind = self::traverseArray($typoscriptService->convertTypoScriptArrayToPlainArray($typoscript), GeneralUtility::trimExplode('.', $subPath, true));

        return $subPathToFind;
    }

    /**
     * Return TypoScript Template for given page uid if any
     * @param int $pageUid
     * @return array|bool
     */
    public static function getTypoScriptTemplate(int $pageUid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_template')->createQueryBuilder();
        return $queryBuilder->select('*')->from('sys_template')->where(
            $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT))
        )->executeQuery()->fetchAssociative();
    }

    /**
     * Return TypoScript Template for given page uid if any
     * @param array $typoScript
     * @param int $pageUid
     * @param string type, setup or constants
     * @return array|bool
     */
    public static function updateTypoScriptTemplate(array $typoScript, int $pageUid, $type = 'setup')
    {
        $typoScriptAsString = self::convertArrayToTypoScript($typoScript);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_template')->createQueryBuilder();
        $queryBuilder->update('sys_template')->where(
            $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT))
        )->set($type, $typoScriptAsString);

        $result = false;

        $versionInformation = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
        if ($versionInformation->getMajorVersion() < 11) {
            $result = $queryBuilder->execute();
        } else {
            $result = $queryBuilder->executeStatement();
        }

        return $result;
    }

    /**
     * Try to find key in assoc
     * @param array $array
     * @param array $subPath
     * @param int $loop
     * @return string|array|null
     */
    public static function traverseArray(array $array, array $subPath)
    {
        $subPathLength = count($subPath);

        foreach ($subPath as $keyToFind) {
            if (is_array($array) && array_key_exists($keyToFind, $array)) {
                $array = $array[$keyToFind];
            } else {
                return null;
            }
        }

        return $array;
    }
}
