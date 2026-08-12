<?php

namespace App\Services\Admin;

use App\Models\AdminRefreshToken;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\HttpFoundation\Cookie;

class AdminJwtService
{
    /**
     * Issue an access JWT token for the administrator.
     *
     * @param User $user
     * @param array $permissions
     * @return array Contains 'token' and 'expires_in'
     */
    public function issueAccessToken(User $user, array $permissions): array
    {
        $secret = config('admin_auth.jwt.access_secret');
        $issuer = config('admin_auth.jwt.issuer', 'konn3ct-api');
        $audience = config('admin_auth.jwt.audience', 'konn3ct-admin');
        $ttl = (int) config('admin_auth.jwt.access_ttl', 900);

        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($secret)
        );

        $now = new DateTimeImmutable();
        $expiresAt = $now->modify("+{$ttl} seconds");
        $jti = (string) Str::uuid();

        $token = $config->builder()
            ->issuedBy($issuer)
            ->permittedFor($audience)
            ->relatedTo((string) $user->id)
            ->identifiedBy($jti)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($expiresAt)
            ->withClaim('token_type', 'access')
            ->withClaim('role', 'admin')
            ->withClaim('permissions', array_values($permissions))
            ->getToken($config->signer(), $config->signingKey());

        return [
            'token' => $token->toString(),
            'expires_in' => $ttl,
            'jti' => $jti,
        ];
    }

    /**
     * Issue a refresh JWT token and store its hash in the database.
     *
     * @param User $user
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @return array Contains 'token', 'cookie', 'jti', 'expires_at'
     */
    public function issueRefreshToken(User $user, ?string $ipAddress = null, ?string $userAgent = null): array
    {
        $secret = config('admin_auth.jwt.refresh_secret');
        $issuer = config('admin_auth.jwt.issuer', 'konn3ct-api');
        $audience = config('admin_auth.jwt.audience', 'konn3ct-admin');
        $ttl = (int) config('admin_auth.jwt.refresh_ttl', 604800);

        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($secret)
        );

        $now = new DateTimeImmutable();
        $expiresAt = $now->modify("+{$ttl} seconds");
        $jti = (string) Str::uuid();

        $token = $config->builder()
            ->issuedBy($issuer)
            ->permittedFor($audience)
            ->relatedTo((string) $user->id)
            ->identifiedBy($jti)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($expiresAt)
            ->withClaim('token_type', 'refresh')
            ->getToken($config->signer(), $config->signingKey());

        $rawJwt = $token->toString();

        // Store hashed jti in admin_refresh_tokens table
        $tokenHash = hash('sha256', $jti);
        AdminRefreshToken::create([
            'user_id' => $user->id,
            'token_id' => $tokenHash,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent ? Str::limit($userAgent, 500) : null,
        ]);

        // Create Cookie
        $cookieName = config('admin_auth.cookie.name', 'konn3ct_admin_refresh_token');
        $minutes = (int) round($ttl / 60); // 7 days = 10080 minutes
        $path = config('admin_auth.cookie.path', '/api/v1/admin/auth');
        $secure = (bool) config('admin_auth.cookie.secure', false);
        $sameSite = config('admin_auth.cookie.same_site', 'lax');

        $cookie = cookie(
            $cookieName,
            $rawJwt,
            $minutes,
            $path,
            null, // domain
            $secure,
            true, // httpOnly
            false, // raw
            $sameSite
        );

        return [
            'token' => $rawJwt,
            'cookie' => $cookie,
            'jti' => $jti,
            'expires_at' => $expiresAt,
        ];
    }
}
