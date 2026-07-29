<?php

namespace App\TaskManager\Application\User\Find;


use App\Shared\Domain\Bus\Query\QueryHandler;
use App\TaskManager\Application\User\UserDTO;

class FindUserQueryHandler implements QueryHandler
{
    public function __construct(private UserFinder $finder) {}

    public function __invoke(FindUserQuery $query): UserDTO
    {
        return UserDTO::fromEntity($this->finder->__invoke($query->id));
    }
}
