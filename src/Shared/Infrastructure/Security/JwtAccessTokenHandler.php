<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class JwtAccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly JwtService $jwtService,
    ) {}

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        try {
            $payload = $this->jwtService->validateToken($accessToken);
            $userIdentifier = $payload['sub'] ?? null;

            if (!$userIdentifier || !is_string($userIdentifier)) {
                throw new CustomUserMessageAuthenticationException('Invalid token: missing subject.');
            }

            return new UserBadge($userIdentifier);
        } catch (\Throwable $e) {
            throw new CustomUserMessageAuthenticationException('Invalid or expired JWT token: ' . $e->getMessage());
        }
    }
}
