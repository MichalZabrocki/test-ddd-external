<?php

namespace App\Base\Cli;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\TaskManager\Application\User\Create\CreateUserCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:create-users',
    description: 'Add a short description for your command',
)]
class CreateUsersCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client,
        private readonly CommandBus $commandBus
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $response = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/users/');
        if ($response->getStatusCode() !== Response::HTTP_OK ) {
            $io->error('Failed to fetch data from API');
            return Command::FAILURE;
        }

        $users = $response->toArray();

        $counter = 0;
        foreach ($users as $user) {
            $roles = $counter === 0 ? ['ROLE_ADMIN'] : ['ROLE_USER'];
            $createUserCommand = new CreateUserCommand(Uuid::v4(), $user['name'], $user['email'], $roles, $_ENV['USER_PASSWORD']);
            $this->commandBus->dispatch($createUserCommand);

            $counter++;
        }

        $io->success('User created: ' . (int)$counter);

        return Command::SUCCESS;
    }
}
