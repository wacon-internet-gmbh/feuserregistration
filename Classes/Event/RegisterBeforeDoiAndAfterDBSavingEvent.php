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

namespace Wacon\Feuserregistration\Event;

use Psr\Http\Message\ServerRequestInterface;
use Wacon\Feuserregistration\Domain\Model\User;

final class RegisterBeforeDoiAndAfterDBSavingEvent
{
    public function __construct(
        protected User $user,
        protected ServerRequestInterface $request
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
