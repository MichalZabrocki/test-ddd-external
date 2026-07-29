<?php

namespace App\TaskManager\Application\User;

use App\Shared\Domain\Bus\Query\Response;
use App\TaskManager\Domain\User\User;

class UserDTO implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email
    ) {}

    public static function fromEntity(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getName(),
            $user->getEmail()->getValue()
        );
    }
}
