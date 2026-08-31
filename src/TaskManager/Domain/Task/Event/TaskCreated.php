<?php

namespace App\TaskManager\Domain\Task\Event;

use App\TaskManager\Domain\User\User;
use DateTimeImmutable;

class TaskCreated implements TaskEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly User $assignedUser,
        public readonly DateTimeImmutable $occurredAt
    ) {}
}
