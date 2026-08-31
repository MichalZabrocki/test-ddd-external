<?php

namespace App\TaskManager\Application\Task\Create;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\TaskManager\Domain\Task\Task;
use App\TaskManager\Domain\Task\TaskRepositoryInterface;
use App\TaskManager\Domain\User\UserRepositoryInterface;

class CreateTaskHandler implements CommandHandler
{

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(CreateTaskCommand $command)
    {
        $user = $this->userRepository->findById($command->getUser());
        if ($user === null) {
            throw new \Exception('User not found');
        }
        $task = Task::create($command->getId(), $command->getName(), $command->getDescription(), $user);
        $this->taskRepository->save($task);
    }
}
