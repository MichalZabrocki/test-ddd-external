<?php

namespace App\Base\GraphQL;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\TaskManager\Application\Task\AssignUser\AssignUserCommand;
use App\TaskManager\Application\Task\ChangeStatus\ChangeStatusCommand;
use App\TaskManager\Application\Task\Create\CreateTaskCommand;
use App\TaskManager\Application\Task\Get\GetTaskQuery;
use App\TaskManager\Application\User\Find\FindUserQuery;
use App\TaskManager\Domain\Task\TaskStatus;
use Overblog\GraphQLBundle\ExpressionLanguage\ExpressionFunction\Security\GetUser;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Symfony\Component\Uid\Uuid;

class TaskResolverMap extends ResolverMap
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus
    ) {}
    protected function map()
    {
        return [
            'Query' => [
                'tasks' => function ($value, $args)  { print_r($value); return $this->tasks($args['input']['id']); },
                'task' => function ($value, $args)  {
            return $this->task($args['id']);
        }
            ],
            'Mutation' => [
                'CreateTask' => function ($value, $args) {
                    $id = Uuid::v4();
                    $this->commandBus->dispatch(new CreateTaskCommand($id, $args['input']['name'], $args['input']['description'], $args['input']['assignedUser']));

                    return $this->queryBus->ask(new GetTaskQuery($id));
                    },
                'ChangeTaskStatus' => function ($value, $args) {
                    $taskStatus = TaskStatus::from($args['input']['status']);
                    $id = $args['input']['id'];
                    $this->commandBus->dispatch(new ChangeStatusCommand($id, $taskStatus));

                    return $this->queryBus->ask(new GetTaskQuery($id));
                },
                'AssignUser' => function ($value, $args) {
                    $id = $args['input']['id'];

                    $this->commandBus->dispatch(new AssignUserCommand($args['input']['id'], $args['input']['assignedUser']));

                    return $this->queryBus->ask(new GetTaskQuery($id));
                }
            ]
        ];
    }

    private function task($id)
    {
        return $this->queryBus->ask(new GetTaskQuery($id));
    }
}
