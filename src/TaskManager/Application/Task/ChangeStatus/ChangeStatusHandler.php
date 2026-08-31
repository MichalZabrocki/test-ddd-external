<?php

namespace App\TaskManager\Application\Task\ChangeStatus;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\TaskManager\Domain\Task\TaskRepositoryInterface;
use App\TaskManager\Domain\Task\TaskStatus;

class ChangeStatusHandler implements CommandHandler
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function __invoke(ChangeStatusCommand $command): void
    {
        $task = $this->taskRepository->findById($command->getId());

        if (null === $task) {
            throw new \RuntimeException(sprintf('Task with id %s not found', $command->getId()));
        }

        $task->changeStatus($command->getStatus());

        $this->taskRepository->save($task);
    }
}
