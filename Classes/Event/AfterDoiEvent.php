<?php

declare(strict_types=1);

namespace Wacon\Feuserregistration\Event;

use Wacon\Feuserregistration\Domain\Model\User;

final class AfterDoiEvent
{
    public function __construct(
        private User $user
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param mixed  $user
     *
     * @return self
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}
