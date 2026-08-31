<?php

namespace App\TaskManager\Application\Task\Get;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\TaskManager\Application\Task\Create\CreateTaskCommand;
use App\TaskManager\Domain\Task\Task;

class GetAllTaskQueryHandler implements QueryHandler
{
    public function __invoke(GetAllTaskQuery $command)
    {
//        $task = Task::create($command->getId(), $command->getName(), $command->getDescription(), $command->getUser());
//        $this->taskRepository->save($task);
    }
}
