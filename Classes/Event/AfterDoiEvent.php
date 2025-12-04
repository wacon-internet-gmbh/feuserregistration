<?php

declare(strict_types=1);

namespace Wacon\Feuserregistration\Event;

use Psr\Http\Message\RequestInterface;
use Wacon\Feuserregistration\Domain\Model\User;

final class AfterDoiEvent
{
    public function __construct(
        private User $user,
        private RequestInterface $request
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

    /**
     * Get the value of request
     *
     * @return mixed
     */
    public function getRequest(): mixed
    {
        return $this->request;
    }

    /**
     * Set the value of request
     *
     * @param mixed  $request
     *
     * @return self
     */
    public function setRequest($request): self
    {
        $this->request = $request;

        return $this;
    }
}
