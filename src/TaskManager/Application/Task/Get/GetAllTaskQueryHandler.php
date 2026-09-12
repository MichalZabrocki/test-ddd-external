<?php

namespace App\TaskManager\Application\Task\Get;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\TaskManager\Application\Task\TaskDTO;
use App\TaskManager\Application\Task\TaskListDTO;
use App\TaskManager\Domain\Task\Task;
use App\TaskManager\Domain\Task\TaskRepositoryInterface;

class GetAllTaskQueryHandler implements QueryHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ){}

    public function __invoke(GetAllTaskQuery $command)
    {
        return TaskListDTO::fromList(array_map(fn(Task $task) => TaskDTO::fromEntity($task), $this->taskRepository->findAll()));
    }
}
