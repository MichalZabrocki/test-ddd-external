<?php

namespace App\TaskManager\Domain\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findById(string $id): ?User;

    public function findByEmail(string $email): ?User;
    public function findAll(): array;
}
