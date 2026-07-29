<?php

namespace App\TaskManager\Application\User\Get;

use App\Shared\Domain\Bus\Command\Command;

class GetUser
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
