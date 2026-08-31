<?php

namespace App\Base\GraphQL;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\TaskManager\Application\User\Find\FindUserQuery;
use App\TaskManager\Application\User\Get\GetUser;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class UserResolverMap extends ResolverMap
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus
    ) {}

    public function map() : array
    {
        return [
            'Query' => [
                'user' => fn() => $this->user(),
            ],
        ];
    }

    public function user()
    {
        $query = new FindUserQuery("03d78470-8799-4148-bcce-6199b4b76bc2");
        $user = $this->queryBus->ask($query);

        return $user;
    }

}
