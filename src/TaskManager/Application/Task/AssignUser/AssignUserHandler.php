<?php

namespace App\TaskManager\Application\Task\AssignUser;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\TaskManager\Domain\Task\TaskRepositoryInterface;
use App\TaskManager\Domain\User\UserRepositoryInterface;

class AssignUserHandler implements CommandHandler
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(AssignUserCommand $command): void
    {
        $task = $this->taskRepository->findById($command->getTaskId());

        if (null === $task) {
            throw new \RuntimeException(sprintf('Task with id %s not found', $command->getTaskId()));
        }

        $user = $this->userRepository->findById($command->getUserId());

        if (null === $user) {
            throw new \RuntimeException(sprintf('User with id %s not found', $command->getUserId()));
        }

        $task->assignUser($user);

        $this->taskRepository->save($task);
    }
}
