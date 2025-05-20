<?php

declare(strict_types=1);

namespace Wacon\Feuserregistration\Utility\Typo3\Extbase;

class PersistenceUtility
{

    /**
     * Add pid to pid list
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     * @param array $pids
     */
    public static function addStoragePageUids(\TYPO3\CMS\Extbase\Persistence\Repository &$repository, array $pids)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $storagePageIds = $querySettings->getStoragePageIds();
        $diff = array_diff($pids, $storagePageIds);
        $storagePageIds = array_merge($storagePageIds, $diff);
        $querySettings->setStoragePageIds($storagePageIds);
        $repository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Disable the RespectSysLanguage
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     */
    public static function disableRespectSysLanguage(\TYPO3\CMS\Extbase\Persistence\Repository &$repository)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setRespectSysLanguage(false);
        $repository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Disable the RespectStoragePage
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     */
    public static function disableRespectStoragePage(\TYPO3\CMS\Extbase\Persistence\Repository &$repository)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $repository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Set language uid to given value
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     * @param int $sysLanguageUid
     */
    public static function setLanguageUid(\TYPO3\CMS\Extbase\Persistence\Repository &$repository, $sysLanguageUid)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setLanguageUid($sysLanguageUid);
        $repository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Removes the access restriction
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     */
    public static function removeAccessRestriction(\TYPO3\CMS\Extbase\Persistence\Repository &$repository)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setEnableFieldsToBeIgnored(['fe_group']);
        $querySettings->setIgnoreEnableFields(true);
        $repository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Removes the access restriction
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     * @param array $enabledFieldsToBeIgnored
     */
    public static function removeAllRestrictions(\TYPO3\CMS\Extbase\Persistence\Repository &$repository, array $enabledFieldsToBeIgnored)
    {
        $querySettings = $repository->createQuery()->getQuerySettings();
        $querySettings->setEnableFieldsToBeIgnored($enabledFieldsToBeIgnored);
        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setRespectStoragePage(false);
        $repository->setDefaultQuerySettings($querySettings);
    }
}
