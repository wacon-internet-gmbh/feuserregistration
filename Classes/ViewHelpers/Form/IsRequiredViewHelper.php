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

namespace Wacon\Feuserregistration\ViewHelpers\Form;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class IsRequiredViewHelper extends AbstractConditionViewHelper
{
    /**
     * Init new arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('settings', 'array', 'The settings array', true);
        $this->registerArgument('field', 'string', 'The field name', true);
    }

    /**
     * Render the ViewHelper
     */
    public function render(): mixed
    {
        $requiredFields = GeneralUtility::trimExplode(',', $this->arguments['settings']['requiredFields'] ?? '', true);

        if (in_array($this->arguments['field'], $requiredFields, true)) {
            return $this->renderThenChild();
        }
        return $this->renderElseChild();
    }
}
