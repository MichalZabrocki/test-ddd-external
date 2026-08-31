<?php

namespace App\TaskManager\Application\Task\Get;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\TaskManager\Application\Task\TaskDTO;
use App\TaskManager\Domain\Task\TaskRepositoryInterface;

class GetTaskQueryHandler implements QueryHandler
{


    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ){}

    public function __invoke(GetTaskQuery $command)
    {
        $task = $this->taskRepository->findById($command->getId());

        return TaskDTO::fromEntity($task);
//        $task = Task::create($command->getId(), $command->getName(), $command->getDescription(), $command->getUser());
//        $this->taskRepository->save($task);
    }
}
