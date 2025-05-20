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

 use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

 class TextToSpeechUtility
 {
    /**
     * Format math formulate for text-to-speech
     * @param string $formula
     * @param string $extensionName
     * @return string
     */
    public static function formatMathFormula(string $formula, string $extensionName): string
    {
        $formula = str_replace(' ', '', $formula);
        $formula = str_replace('+', LocalizationUtility::translate('math.plus', $extensionName), $formula);
        $formula = str_replace('-', LocalizationUtility::translate('math.minus', $extensionName), $formula);
        $formula = str_replace('*', LocalizationUtility::translate('math.times', $extensionName), $formula);
        $formula = str_replace('/', LocalizationUtility::translate('math.divided_by', $extensionName), $formula);

        return $formula;
    }
 }
