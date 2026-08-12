<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Http\Middleware\VisitLogMiddleware;
use App\Models\AdminRefreshToken;
use App\Models\User;
use App\Services\Admin\AdminJwtService;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Tests\TestCase;

class AdminRefreshTest extends TestCase
{
    use RefreshDatabase;

    protected $jwtService;
    protected $cookieName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VisitLogMiddleware::class);

        config([
            'admin_auth.jwt.access_secret' => 'testing_admin_jwt_access_secret_key_32bytes_min!',
            'admin_auth.jwt.refresh_secret' => 'testing_admin_jwt_refresh_secret_key_32bytes_different_min!',
            'admin_auth.jwt.issuer' => 'konn3ct-api',
            'admin_auth.jwt.audience' => 'konn3ct-admin',
            'admin_auth.jwt.access_ttl' => 900,
            'admin_auth.jwt.refresh_ttl' => 604800,
            'admin_auth.cookie.name' => 'konn3ct_admin_refresh_token',
            'admin_auth.cookie.path' => '/api/v1/admin/auth',
            'admin_auth.cookie.secure' => false,
            'admin_auth.cookie.same_site' => 'lax',
        ]);

        $this->jwtService = new AdminJwtService();
        $this->cookieName = config('admin_auth.cookie.name');
    }

    protected function postRefresh(?string $cookieValue = null, array $body = [], array $headers = [])
    {
        $cookies = [];
        if ($cookieValue !== null) {
            $cookies[$this->cookieName] = $cookieValue;
        }

        $server = $this->transformHeadersToServerVars(array_merge([
            'Accept' => 'application/json',
        ], $headers));

        return $this->call('POST', route('api.v1.admin.auth.refresh'), $body, $cookies, [], $server);
    }

    public function test_route_exists_and_has_correct_name()
    {
        $this->assertTrue(route('api.v1.admin.auth.refresh') !== null);
        $this->assertStringContainsString('/api/v1/admin/auth/refresh', route('api.v1.admin.auth.refresh'));
    }

    public function test_missing_refresh_cookie_returns_401()
    {
        $response = $this->postRefresh();

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'INVALID_REFRESH_TOKEN',
                'message' => 'Authentication session is invalid or has expired.',
            ])
            ->assertHeader('Pragma', 'no-cache');

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    public function test_empty_refresh_cookie_returns_401()
    {
        $response = $this->postRefresh('');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'INVALID_REFRESH_TOKEN',
            ]);
    }

    public function test_refresh_token_supplied_only_in_json_body_is_rejected()
    {
        $user = User::factory()->admin()->create();
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $response = $this->postRefresh(cookieValue: null, body: [
            'refresh_token' => $tokenData['token'],
        ]);

        $response->assertStatus(401);
    }

    public function test_refresh_token_supplied_only_in_authorization_header_is_rejected()
    {
        $user = User::factory()->admin()->create();
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $response = $this->postRefresh(cookieValue: null, headers: [
            'Authorization' => 'Bearer ' . $tokenData['token'],
        ]);

        $response->assertStatus(401);
    }

    public function test_malformed_jwt_returns_401()
    {
        $response = $this->postRefresh('invalid.malformed.jwt');

        $response->assertStatus(401);
    }

    public function test_tampered_signature_returns_401()
    {
        $user = User::factory()->admin()->create();
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $tamperedJwt = $tokenData['token'] . 'tampered';

        $response = $this->postRefresh($tamperedJwt);

        $response->assertStatus(401);
    }

    public function test_jwt_signed_with_access_secret_returns_401()
    {
        $user = User::factory()->admin()->create();

        // Sign refresh claims using access secret instead of refresh secret
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(config('admin_auth.jwt.access_secret'))
        );

        $now = new DateTimeImmutable();
        $jti = (string) Str::uuid();

        $token = $config->builder()
            ->issuedBy('konn3ct-api')
            ->permittedFor('konn3ct-admin')
            ->relatedTo((string) $user->id)
            ->identifiedBy($jti)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+7 days'))
            ->withClaim('token_type', 'refresh')
            ->getToken($config->signer(), $config->signingKey());

        AdminRefreshToken::create([
            'user_id' => $user->id,
            'token_id' => hash('sha256', $jti),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->postRefresh($token->toString());

        $response->assertStatus(401);
    }

    public function test_access_token_used_as_refresh_token_returns_401()
    {
        $user = User::factory()->admin()->create();
        $accessTokenData = $this->jwtService->issueAccessToken($user, ['admin.*']);

        $response = $this->postRefresh($accessTokenData['token']);

        $response->assertStatus(401);
    }

    public function test_wrong_issuer_returns_401()
    {
        $user = User::factory()->admin()->create();
        $jwt = $this->buildCustomRefreshJwt($user, issuer: 'wrong-issuer');

        $response = $this->postRefresh($jwt);

        $response->assertStatus(401);
    }

    public function test_wrong_audience_returns_401()
    {
        $user = User::factory()->admin()->create();
        $jwt = $this->buildCustomRefreshJwt($user, audience: 'wrong-audience');

        $response = $this->postRefresh($jwt);

        $response->assertStatus(401);
    }

    public function test_missing_sub_returns_401()
    {
        $user = User::factory()->admin()->create();
        $jwt = $this->buildCustomRefreshJwt($user, includeSub: false);

        $response = $this->postRefresh($jwt);

        $response->assertStatus(401);
    }

    public function test_missing_jti_returns_401()
    {
        $user = User::factory()->admin()->create();
        $jwt = $this->buildCustomRefreshJwt($user, includeJti: false);

        $response = $this->postRefresh($jwt);

        $response->assertStatus(401);
    }

    public function test_future_iat_or_nbf_returns_401()
    {
        $user = User::factory()->admin()->create();
        $jwt = $this->buildCustomRefreshJwt($user, iatOffsetSeconds: +300);

        $response = $this->postRefresh($jwt);

        $response->assertStatus(401);
    }

    public function test_expired_jwt_returns_401()
    {
        $user = User::factory()->admin()->create();
        $jwt = $this->buildCustomRefreshJwt($user, expOffsetSeconds: -300);

        $response = $this->postRefresh($jwt);

        $response->assertStatus(401);
    }

    public function test_valid_signed_jwt_without_database_session_record_returns_401()
    {
        $user = User::factory()->admin()->create();

        // Issue valid JWT without creating row in admin_refresh_tokens
        $config = $this->jwtService->getJwtConfiguration('refresh');
        $now = new DateTimeImmutable();
        $jti = (string) Str::uuid();

        $token = $config->builder()
            ->issuedBy('konn3ct-api')
            ->permittedFor('konn3ct-admin')
            ->relatedTo((string) $user->id)
            ->identifiedBy($jti)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+7 days'))
            ->withClaim('token_type', 'refresh')
            ->getToken($config->signer(), $config->signingKey());

        $response = $this->postRefresh($token->toString());

        $response->assertStatus(401);
    }

    public function test_revoked_database_token_returns_401()
    {
        $user = User::factory()->admin()->create();
        $tokenData = $this->jwtService->issueRefreshToken($user);

        // Revoke token record manually
        AdminRefreshToken::where('token_id', hash('sha256', $tokenData['jti']))
            ->update(['revoked_at' => now()]);

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(401);
    }

    public function test_database_expired_session_returns_401()
    {
        $user = User::factory()->admin()->create();
        $tokenData = $this->jwtService->issueRefreshToken($user);

        // Set DB expiry to past
        AdminRefreshToken::where('token_id', hash('sha256', $tokenData['jti']))
            ->update(['expires_at' => now()->subDay()]);

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(401);
    }

    public function test_jwt_sub_and_database_user_id_mismatch_returns_401()
    {
        $user1 = User::factory()->admin()->create();
        $user2 = User::factory()->admin()->create();

        $tokenData = $this->jwtService->issueRefreshToken($user1);

        // Change DB user_id to user2
        AdminRefreshToken::where('token_id', hash('sha256', $tokenData['jti']))
            ->update(['user_id' => $user2->id]);

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(401);
    }

    public function test_missing_or_deleted_user_returns_401()
    {
        $user = User::factory()->admin()->create();
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $user->delete();

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(401);
    }

    public function test_non_admin_user_returns_401()
    {
        $user = User::factory()->create(['type' => 'user']);
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(401);
    }

    public function test_suspended_or_disabled_administrator_returns_401()
    {
        $user = User::factory()->admin()->create(['status' => 'suspended']);
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(401);
    }

    public function test_legacy_administrator_with_status_null_can_refresh()
    {
        $user = User::factory()->admin()->create(['status' => null]);
        $tokenData = $this->jwtService->issueRefreshToken($user);

        $response = $this->postRefresh($tokenData['token']);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Session refreshed successfully.',
            ]);
    }

    public function test_successful_refresh_returns_200_and_rotated_tokens()
    {
        $user = User::factory()->admin()->create([
            'firstname' => 'System',
            'lastname' => 'Admin',
            'email' => 'admin_refresh@example.com',
        ]);

        $oldTokenData = $this->jwtService->issueRefreshToken($user);

        $response = $this->postRefresh($oldTokenData['token']);

        $response->assertStatus(200)
            ->assertHeader('Pragma', 'no-cache')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'admin' => [
                        'id',
                        'firstname',
                        'lastname',
                        'email',
                        'role',
                        'permissions',
                        'profile_photo_url',
                    ],
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'token_type' => 'Bearer',
                    'expires_in' => 900,
                    'admin' => [
                        'id' => $user->id,
                        'email' => 'admin_refresh@example.com',
                        'role' => 'admin',
                        'permissions' => ['admin.*'],
                    ],
                ],
            ]);

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));

        // Verify refresh token is NOT in JSON body
        $json = $response->json();
        $this->assertArrayNotHasKey('refresh_token', $json);
        $this->assertArrayNotHasKey('refresh_token', $json['data']);

        // Verify access token is valid
        $newAccessToken = $json['data']['access_token'];
        $validatedAccessToken = $this->jwtService->validateAccessToken($newAccessToken);
        $this->assertEquals((string) $user->id, $validatedAccessToken->claims()->get('sub'));

        // Verify new HTTP-only cookie set
        $response->assertCookie($this->cookieName);

        // Verify old database row is revoked
        $oldRecord = AdminRefreshToken::where('token_id', hash('sha256', $oldTokenData['jti']))->first();
        $this->assertNotNull($oldRecord->revoked_at);

        // Verify new database row exists and is active
        $activeRecords = AdminRefreshToken::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->get();
        $this->assertCount(1, $activeRecords);
        $this->assertNotEquals(hash('sha256', $oldTokenData['jti']), $activeRecords->first()->token_id);
    }

    public function test_replaying_old_refresh_token_returns_401_and_does_not_revoke_replacement()
    {
        $user = User::factory()->admin()->create();
        $oldTokenData = $this->jwtService->issueRefreshToken($user);

        // 1st call: Successful refresh
        $response1 = $this->postRefresh($oldTokenData['token']);
        $response1->assertStatus(200);

        // Extract replacement refresh cookie from response1
        $cookies = $response1->headers->getCookies();
        $newCookieValue = null;
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === $this->cookieName) {
                $newCookieValue = $cookie->getValue();
                break;
            }
        }
        $this->assertNotNull($newCookieValue);

        // 2nd call: Replay old refresh token
        $response2 = $this->postRefresh($oldTokenData['token']);
        $response2->assertStatus(401);

        // 3rd call: Verify replacement token remains valid and can be refreshed!
        $response3 = $this->postRefresh($newCookieValue);
        $response3->assertStatus(200);
    }

    public function test_invalid_refresh_response_expires_stale_cookie()
    {
        $response = $this->postRefresh('invalid-token');

        $response->assertStatus(401);

        // Verify expired cookie is sent in header
        $cookies = $response->headers->getCookies();
        $staleCookieFound = false;
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === $this->cookieName) {
                $staleCookieFound = true;
                $this->assertLessThan(time(), $cookie->getExpiresTime());
                break;
            }
        }
        $this->assertTrue($staleCookieFound);
    }

    /**
     * Helper to build customized refresh JWTs for testing edge cases.
     */
    protected function buildCustomRefreshJwt(
        User $user,
        string $issuer = 'konn3ct-api',
        string $audience = 'konn3ct-admin',
        bool $includeSub = true,
        bool $includeJti = true,
        int $iatOffsetSeconds = 0,
        int $expOffsetSeconds = 604800
    ): string {
        $config = $this->jwtService->getJwtConfiguration('refresh');
        $now = new DateTimeImmutable();
        $iat = $now->modify(($iatOffsetSeconds >= 0 ? '+' : '') . "{$iatOffsetSeconds} seconds");
        $exp = $now->modify(($expOffsetSeconds >= 0 ? '+' : '') . "{$expOffsetSeconds} seconds");
        $jti = (string) Str::uuid();

        $builder = $config->builder()
            ->issuedBy($issuer)
            ->permittedFor($audience)
            ->issuedAt($iat)
            ->canOnlyBeUsedAfter($iat)
            ->expiresAt($exp)
            ->withClaim('token_type', 'refresh');

        if ($includeSub) {
            $builder = $builder->relatedTo((string) $user->id);
        }

        if ($includeJti) {
            $builder = $builder->identifiedBy($jti);
            AdminRefreshToken::create([
                'user_id' => $user->id,
                'token_id' => hash('sha256', $jti),
                'expires_at' => now()->addSeconds($expOffsetSeconds),
            ]);
        }

        return $builder->getToken($config->signer(), $config->signingKey())->toString();
    }
}
