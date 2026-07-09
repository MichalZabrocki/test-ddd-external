<?php

namespace App\TaskManager\Application\User\Create;

use App\Shared\Domain\Bus\Command\Command;

class CreateUserCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $email,
        private readonly array $roles,
        private readonly string $password
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
