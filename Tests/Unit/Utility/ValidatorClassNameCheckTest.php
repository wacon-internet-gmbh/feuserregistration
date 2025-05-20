<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension: test_automation.
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

namespace Slavlee\Feuserregistration\Tests\Unit\Utility;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Wacon\Feuserregistration\Domain\Model\ExtendedUser;
use Wacon\Feuserregistration\Domain\Model\NotExtendedUser;
use Wacon\Feuserregistration\Domain\Model\User;

class ValidatorClassNameCheckTest extends UnitTestCase
{
    /**
     * Test a model inherited by \Wacon\Feuserregistration\Domain\Model\User
     * if it is inherited by \Wacon\Feuserregistration\Domain\Model\User
     */
    #[Test]
    public function modelInheritedByUserModelTest()
    {
        $extendedUser = new ExtendedUser();
        $isInstanceOf = $extendedUser instanceof User;

        $this->assertEquals(true, $isInstanceOf);
    }

    /**
     * Test a model which is a \Wacon\Feuserregistration\Domain\Model\User
     * if it is inherited by \Wacon\Feuserregistration\Domain\Model\User
     */
    #[Test]
    public function modelIsAUserModelTest()
    {
        $extendedUser = new User();
        $isInstanceOf = $extendedUser instanceof User;

        $this->assertEquals(true, $isInstanceOf);
    }

    /**
     * Test a model not inherited by \Wacon\Feuserregistration\Domain\Model\User
     * if it is inherited by \Wacon\Feuserregistration\Domain\Model\User
     */
    #[Test]
    public function userNotInheritedByUserModelTest()
    {
        $extendedUser = new NotExtendedUser();
        $isInstanceOf = $extendedUser instanceof User;

        $this->assertEquals(false, $isInstanceOf);
    }
}
