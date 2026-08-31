<?php

namespace App\TaskManager\Application\Task\Get;

use App\Shared\Domain\Bus\Query\Query;

class GetAllTaskQuery implements Query
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = 10
    ) {}
}
