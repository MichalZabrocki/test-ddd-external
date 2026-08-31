<?php

namespace App\TaskManager\Application\Task\Create;

use App\Shared\Domain\Bus\Command\Command;
use App\TaskManager\Domain\Task\TaskStatus;
use App\TaskManager\Domain\User\User;

class CreateTaskCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $description,
        private readonly string $user
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
