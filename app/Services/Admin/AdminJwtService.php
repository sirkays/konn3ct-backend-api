<?php

namespace App\Services\Admin;

use App\Models\AdminRefreshToken;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Support\Str;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Symfony\Component\HttpFoundation\Cookie;

class AdminJwtService
{
    /**
     * Validate and retrieve configured signing secrets ensuring security constraints.
     *
     * @param string $type 'access' or 'refresh'
     * @return string
     * @throws \RuntimeException
     */
    public function getValidatedSecret(string $type): string
    {
        $accessSecret = config('admin_auth.jwt.access_secret');
        $refreshSecret = config('admin_auth.jwt.refresh_secret');

        if (empty($accessSecret) || !is_string($accessSecret) || strlen($accessSecret) < 32) {
            throw new \RuntimeException("Configuration error: ADMIN_JWT_ACCESS_SECRET must be configured with a string of at least 32 bytes.");
        }

        if (empty($refreshSecret) || !is_string($refreshSecret) || strlen($refreshSecret) < 32) {
            throw new \RuntimeException("Configuration error: ADMIN_JWT_REFRESH_SECRET must be configured with a string of at least 32 bytes.");
        }

        if ($accessSecret === $refreshSecret) {
            throw new \RuntimeException("Configuration error: ADMIN_JWT_ACCESS_SECRET and ADMIN_JWT_REFRESH_SECRET must be different strings.");
        }

        return $type === 'access' ? $accessSecret : $refreshSecret;
    }

    /**
     * Get Lcobucci JWT Configuration for symmetric HMAC-SHA256.
     *
     * @param string $type 'access' or 'refresh'
     * @return Configuration
     */
    public function getJwtConfiguration(string $type): Configuration
    {
        $secret = $this->getValidatedSecret($type);

        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($secret)
        );
    }

    /**
     * Issue an access JWT token for the administrator.
     *
     * @param User $user
     * @param array $permissions
     * @return array Contains 'token', 'expires_in', and 'jti'
     */
    public function issueAccessToken(User $user, array $permissions): array
    {
        $config = $this->getJwtConfiguration('access');
        $issuer = config('admin_auth.jwt.issuer', 'konn3ct-api');
        $audience = config('admin_auth.jwt.audience', 'konn3ct-admin');
        $ttl = (int) config('admin_auth.jwt.access_ttl', 900);

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
        $config = $this->getJwtConfiguration('refresh');
        $issuer = config('admin_auth.jwt.issuer', 'konn3ct-api');
        $audience = config('admin_auth.jwt.audience', 'konn3ct-admin');
        $ttl = (int) config('admin_auth.jwt.refresh_ttl', 604800);

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

    /**
     * Cryptographically parse and validate a refresh JWT string.
     *
     * @param string $jwtString
     * @return Plain
     * @throws \InvalidArgumentException
     */
    public function validateRefreshToken(string $jwtString): Plain
    {
        $config = $this->getJwtConfiguration('refresh');
        $issuer = config('admin_auth.jwt.issuer', 'konn3ct-api');
        $audience = config('admin_auth.jwt.audience', 'konn3ct-admin');

        try {
            $token = $config->parser()->parse($jwtString);
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException("Refresh token parsing failed: " . $e->getMessage());
        }

        if (! $token instanceof Plain) {
            throw new \InvalidArgumentException("Invalid JWT token structure.");
        }

        $clock = SystemClock::fromSystemTimezone();
        $constraints = [
            new SignedWith($config->signer(), $config->signingKey()),
            new IssuedBy($issuer),
            new PermittedFor($audience),
            new ValidAt($clock),
        ];

        if (! $config->validator()->validate($token, ...$constraints)) {
            throw new \InvalidArgumentException("Refresh token failed signature or standard claim constraints.");
        }

        $claims = $token->claims();

        // Explicit presence validation for required claims
        if (! $claims->has('iat') || ! $claims->has('nbf') || ! $claims->has('exp') ||
            ! $claims->has('sub') || ! $claims->has('jti') || ! $claims->has('token_type')) {
            throw new \InvalidArgumentException("Refresh token is missing required claims.");
        }

        if ($claims->get('token_type') !== 'refresh') {
            throw new \InvalidArgumentException("Token type mismatch. Expected refresh token.");
        }

        $sub = (string) $claims->get('sub');
        $jti = (string) $claims->get('jti');

        if (trim($sub) === '' || trim($jti) === '') {
            throw new \InvalidArgumentException("Subject or JTI claim is empty.");
        }

        $now = new DateTimeImmutable();
        $iat = $claims->get('iat');
        $nbf = $claims->get('nbf');
        $exp = $claims->get('exp');

        if (! $iat instanceof DateTimeImmutable || ! $nbf instanceof DateTimeImmutable || ! $exp instanceof DateTimeImmutable) {
            throw new \InvalidArgumentException("Timestamp claims must be valid DateTimeImmutable objects.");
        }

        // Allow up to 5 seconds clock skew for iat
        if ($iat->getTimestamp() > ($now->getTimestamp() + 5)) {
            throw new \InvalidArgumentException("Token issued in the future.");
        }

        if ($nbf->getTimestamp() > $now->getTimestamp()) {
            throw new \InvalidArgumentException("Token not active yet.");
        }

        if ($exp->getTimestamp() <= $now->getTimestamp()) {
            throw new \InvalidArgumentException("Token has expired.");
        }

        return $token;
    }

    /**
     * Cryptographically parse and validate an access JWT string.
     *
     * @param string $jwtString
     * @return Plain
     * @throws \InvalidArgumentException
     */
    public function validateAccessToken(string $jwtString): Plain
    {
        $config = $this->getJwtConfiguration('access');
        $issuer = config('admin_auth.jwt.issuer', 'konn3ct-api');
        $audience = config('admin_auth.jwt.audience', 'konn3ct-admin');

        try {
            $token = $config->parser()->parse($jwtString);
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException("Access token parsing failed: " . $e->getMessage());
        }

        if (! $token instanceof Plain) {
            throw new \InvalidArgumentException("Invalid JWT token structure.");
        }

        $clock = SystemClock::fromSystemTimezone();
        $constraints = [
            new SignedWith($config->signer(), $config->signingKey()),
            new IssuedBy($issuer),
            new PermittedFor($audience),
            new ValidAt($clock),
        ];

        if (! $config->validator()->validate($token, ...$constraints)) {
            throw new \InvalidArgumentException("Access token failed signature or standard claim constraints.");
        }

        $claims = $token->claims();

        if (! $claims->has('iat') || ! $claims->has('nbf') || ! $claims->has('exp') ||
            ! $claims->has('sub') || ! $claims->has('jti') || ! $claims->has('token_type') || ! $claims->has('role')) {
            throw new \InvalidArgumentException("Access token is missing required claims.");
        }

        if ($claims->get('token_type') !== 'access' || $claims->get('role') !== 'admin') {
            throw new \InvalidArgumentException("Token type or role mismatch.");
        }

        return $token;
    }

    /**
     * Helper to return an expired cookie object to clear staled client cookies.
     *
     * @return Cookie
     */
    public function forgetRefreshCookie(): Cookie
    {
        $cookieName = config('admin_auth.cookie.name', 'konn3ct_admin_refresh_token');
        $path = config('admin_auth.cookie.path', '/api/v1/admin/auth');
        $secure = (bool) config('admin_auth.cookie.secure', false);
        $sameSite = config('admin_auth.cookie.same_site', 'lax');

        return cookie(
            $cookieName,
            '',
            -2628000,
            $path,
            null,
            $secure,
            true,
            false,
            $sameSite
        );
    }
}
