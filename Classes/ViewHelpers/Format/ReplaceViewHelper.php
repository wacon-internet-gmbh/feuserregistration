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

namespace Wacon\Feuserregistration\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ReplaceViewHelper extends AbstractViewHelper
{
    /**
     * Init new arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('subject', 'string', 'The original string', true);
        $this->registerArgument('search', 'string', 'String that will be replaced', true);
        $this->registerArgument('replace', 'string', 'Replaced value', true);
    }

    /**
     * Render the ViewHelper
     */
    public function render()
    {
        return str_replace($this->arguments['search'], $this->arguments['replace'], $this->arguments['subject']);
    }
}
