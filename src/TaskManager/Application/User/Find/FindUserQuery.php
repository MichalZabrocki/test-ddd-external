<?php

namespace App\TaskManager\Application\User\Find;

use App\Shared\Domain\Bus\Query\Query;

class FindUserQuery implements Query
{
    public function __construct(public readonly string $id) {}
}
