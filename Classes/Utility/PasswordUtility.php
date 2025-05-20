<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension feuserregistration.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */

namespace Wacon\Feuserregistration\Utility;


use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PasswordUtility
{
    /**
     * Return a random hashed password
     * @return string
     */
    public static function randomHash()
    {
        // Given plain text password
        $password = self::random();
        return self::hashPassword($password);
    }

    /**
     * Create a strong random password.
     * Thanks to https://gist.github.com/tylerhall/521810
     * @param int $length
     * @param bool $add_dashes
     * @param string $available_sets
     * @return string
     */
    public static function random($length = 9, $add_dashes = false, $available_sets = 'luds'): string
    {
        $sets = array();
        if (strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        if (strpos($available_sets, 's') !== false) {
            $sets[] = '!@#$%&*?';
        }

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if (!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    /**
     * Create a password hasgh
     * @param string $password
     * @return string
     */
    public static function hashPassword($password): string
    {
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        return $hashInstance->getHashedPassword($password);
    }
}
