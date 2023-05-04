<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension t3templates_base.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */

 namespace Wacon\Feuserregistration\Utility;


 use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 class PasswordUtility {
    /**
     * Return a random hashed password
     * @return string
     */
    public static function randomHash() {
        // Given plain text password
        $password = md5(uniqid((string)time()));
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        return $hashInstance->getHashedPassword($password);
    }
 }