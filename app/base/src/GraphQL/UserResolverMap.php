<?php

namespace App\Base\GraphQL;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Security\JwtService;
use App\TaskManager\Application\User\UserDTO;
use App\TaskManager\Domain\User\User;
use App\TaskManager\Domain\User\UserRepositoryInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserResolverMap extends ResolverMap
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly Security $security,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JwtService $jwtService
    ) {}

    public function map() : array
    {
        return [
            'Query' => [
                'user' => fn() => $this->user(),
                'users' => fn() => $this->users(),
            ],
            'Mutation' => [
                'Login' => fn($value, $args) => $this->login($args['input']['email'], $args['input']['password']),
            ],
        ];
    }

    public function user(): ?UserDTO
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new UserError('User not found.');
        }

        return UserDTO::fromEntity($user);
    }

    public function users(): array
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new UserError('User dont have access.');
        }

        return array_map(fn(User $user) => UserDTO::fromEntity($user), $this->userRepository->findAll());
    }


    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        $token = $this->jwtService->generateTokenForUser($user);

        return [
            'token' => $token,
            'user' => UserDTO::fromEntity($user),
        ];
    }
}
