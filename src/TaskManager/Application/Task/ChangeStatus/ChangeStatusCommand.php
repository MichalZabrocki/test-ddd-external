<?php

namespace App\TaskManager\Application\Task\ChangeStatus;

use App\Shared\Domain\Bus\Command\Command;
use App\TaskManager\Domain\Task\TaskStatus;

class ChangeStatusCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly TaskStatus $status
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }
}
