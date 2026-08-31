<?php

namespace App\TaskManager\Domain\Task;

interface TaskRepositoryInterface
{
    public function save(Task $task): void;
    public function findById(string $id): ?Task;
}
