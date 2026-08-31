<?php

namespace App\TaskManager\Domain\Task\Event;

use App\TaskManager\Domain\Task\TaskStatus;
use DateTimeImmutable;

class TaskStatusChanged implements TaskEvent
{
    public function __construct(
        public readonly string            $id,
        public readonly TaskStatus        $status,
        public readonly DateTimeImmutable $occurredAt
    ) {}
}
