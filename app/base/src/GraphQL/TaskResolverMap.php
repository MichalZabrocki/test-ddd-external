<?php

namespace App\Base\GraphQL;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;
use App\TaskManager\Application\Task\AssignUser\AssignUserCommand;
use App\TaskManager\Application\Task\ChangeStatus\ChangeStatusCommand;
use App\TaskManager\Application\Task\Create\CreateTaskCommand;
use App\TaskManager\Application\Task\Get\GetAllTaskQuery;
use App\TaskManager\Application\Task\Get\GetTaskQuery;
use App\TaskManager\Application\Task\TaskDTO;
use App\TaskManager\Application\Task\TaskListDTO;
use App\TaskManager\Domain\Task\TaskStatus;
use App\TaskManager\Domain\User\User;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

class TaskResolverMap extends ResolverMap
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly Security $security,
    ) {}
    protected function map()
    {
        return [
            'Query' => [
                'tasks' => fn() => $this->tasks(),
                'task' => fn($value, $args) => $this->task($args['id']),
            ],
            'Mutation' => [
                'CreateTask' => fn($value, $args) => $this->createTask($args),
                'ChangeTaskStatus' => fn($value, $args) => $this->changeTaskStatus($args),
                'AssignUser' => fn($value, $args) => $this->assignUser($args)
            ]
        ];
    }

    private function task(string $id): ?Response
    {
        $user = $this->security->getUser();

        /** @var TaskDTO $task */
        $task = $this->queryBus->ask(new GetTaskQuery($id));

        if ($task->assignedTo !== $user->getUserIdentifier() && !$this->security->isGranted('ROLE_ADMIN')) {
            throw new UserError('User dont have access.');
        }

        return $task;
    }
    private function tasks(): ?Response
    {
        if(!$this->security->isGranted('ROLE_ADMIN')) {
            throw new UserError('User dont have access.');
        }
        $tasl  =  $this->queryBus->ask(new GetAllTaskQuery());;

        return $tasl;
    }

    private function createTask(Argument $args): ?Response
    {
        $id = Uuid::v4();

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new UserError('User not found.');
        }

        $this->commandBus->dispatch(new CreateTaskCommand($id, $args['input']['name'], $args['input']['description'], $user));

        return $this->queryBus->ask(new GetTaskQuery($id));
    }

    private function changeTaskStatus(Argument $args): ?Response
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new UserError('User not found.');
        }

        $taskStatus = TaskStatus::from($args['input']['status']);
        $id = $args['input']['id'];
        $this->commandBus->dispatch(new ChangeStatusCommand($id, $taskStatus));

        return $this->queryBus->ask(new GetTaskQuery($id));
    }

    private function assignUser(Argument $args): ?Response
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new UserError('User not found.');
        }

        $id = $args['input']['id'];

        $this->commandBus->dispatch(new AssignUserCommand($args['input']['id'], $args['input']['assignedUser']));

        return $this->queryBus->ask(new GetTaskQuery($id));
    }
}
