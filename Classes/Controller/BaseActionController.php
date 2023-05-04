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

namespace Wacon\Feuserregistration\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BaseActionController extends ActionController {
    protected $extensionName = 'feuserregistration';
}