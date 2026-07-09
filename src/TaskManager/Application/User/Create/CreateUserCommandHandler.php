<?php

namespace App\TaskManager\Application\User\Create;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\TaskManager\Domain\User\Email;
use App\TaskManager\Domain\User\User;
use App\TaskManager\Domain\User\UserRepositoryInterface;

class CreateUserCommandHandler implements CommandHandler
{

    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function __invoke(CreateUserCommand $command)
    {
        $email = new Email($command->getEmail());
        $user = new User($command->getId(), $command->getName(), $email, $command->getRoles(), $command->getPassword());
        $this->userRepository->save($user);
//        $this->userCreator->__invoke($command->getEmail());
    }
}
