<?php

namespace App\TaskManager\Application\User\Find;

use App\TaskManager\Domain\User\User;
use App\TaskManager\Domain\User\UserRepositoryInterface;

class UserFinder
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function __invoke(string $id): User
    {
        return $this->repository->find($id);
    }
}
