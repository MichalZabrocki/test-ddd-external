<?php

namespace App\TaskManager\Application\User\Create;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\TaskManager\Domain\User\Email;
use App\TaskManager\Domain\User\User;
use App\TaskManager\Domain\User\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(CreateUserCommand $command)
    {
        $email = new Email($command->getEmail());
        $user = new User($command->getId(), $command->getName(), $email, $command->getRoles());

        if ($command->getPassword() !== null && $command->getPassword() !== '') {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $command->getPassword());
            $user->setPassword($hashedPassword);
        }

        $this->userRepository->save($user);
    }
}
