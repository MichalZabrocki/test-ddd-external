<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtService
{
    public function __construct(
        #[Autowire('%kernel.secret%')]
        private readonly string $secret,
        private readonly int $ttl = 86400
    ) {}

    public function generateTokenForUser(UserInterface $user, array $extraClaims = []): string
    {
        $now = time();
        $payload = array_merge([
            'sub' => $user->getUserIdentifier(),
            'iat' => $now,
            'exp' => $now + $this->ttl,
            'roles' => $user->getRoles(),
        ], $extraClaims);

        return $this->createToken($payload);
    }

    public function createToken(array $payload): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $base64Header = $this->base64UrlEncode((string) json_encode($header, JSON_THROW_ON_ERROR));
        $base64Payload = $this->base64UrlEncode((string) json_encode($payload, JSON_THROW_ON_ERROR));

        $signature = hash_hmac('sha256', "{$base64Header}.{$base64Payload}", $this->secret, true);
        $base64Signature = $this->base64UrlEncode($signature);

        return "{$base64Header}.{$base64Payload}.{$base64Signature}";
    }

    public function validateToken(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new BadCredentialsException('Invalid token format.');
        }

        [$base64Header, $base64Payload, $base64Signature] = $parts;

        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', "{$base64Header}.{$base64Payload}", $this->secret, true)
        );

        if (!hash_equals($expectedSignature, $base64Signature)) {
            throw new BadCredentialsException('Invalid token signature.');
        }

        $payloadJson = $this->base64UrlDecode($base64Payload);
        try {
            $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new BadCredentialsException('Invalid token payload.');
        }

        if (!is_array($payload)) {
            throw new BadCredentialsException('Invalid token payload.');
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new BadCredentialsException('Token has expired.');
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder > 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return (string) base64_decode(strtr($data, '-_', '+/'));
    }
}
