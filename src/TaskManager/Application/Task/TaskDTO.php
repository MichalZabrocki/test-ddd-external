<?php

namespace App\TaskManager\Application\Task;

use App\Shared\Domain\Bus\Query\Response;
use App\TaskManager\Domain\Task\Task;

class TaskDTO implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $status,
        public readonly string $assignedTo
    ) {}

    public static function fromEntity(Task $task): self
    {
        return new self(
            $task->getId(),
            $task->getName(),
            $task->getDescription(),
            $task->getStatus()->value,
            $task->getAssignedUserId()
        );
    }
}
