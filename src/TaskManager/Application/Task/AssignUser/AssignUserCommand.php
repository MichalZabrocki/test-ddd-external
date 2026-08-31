<?php

namespace App\TaskManager\Application\Task\AssignUser;

use App\Shared\Domain\Bus\Command\Command;

class AssignUserCommand implements Command
{
    public function __construct(
        private readonly string $taskId,
        private readonly string $userId
    ) {}

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
