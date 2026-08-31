<?php

namespace App\TaskManager\Application\Task\Get;

use App\Shared\Domain\Bus\Query\Query;

class GetTaskQuery implements Query
{
    public function __construct(private string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
