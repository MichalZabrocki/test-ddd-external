<?php

namespace App\TaskManager\Domain\User;

class Email
{
    public function __construct(private string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }
    }

    public function getValue(): string
    {
        return $this->email;
    }
}
