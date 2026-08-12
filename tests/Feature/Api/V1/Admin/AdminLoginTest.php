<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Http\Middleware\VisitLogMiddleware;
use App\Models\User;
use App\Services\Admin\AdminJwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Mockery;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VisitLogMiddleware::class);

        config([
            'admin_auth.jwt.access_secret' => 'testing_admin_jwt_access_secret_key_32bytes_min!',
            'admin_auth.jwt.refresh_secret' => 'testing_admin_jwt_refresh_secret_key_32bytes_different_min!',
            'admin_auth.jwt.issuer' => 'konn3ct-api',
            'admin_auth.jwt.audience' => 'konn3ct-admin',
            'admin_auth.cookie.name' => 'konn3ct_admin_refresh_token',
        ]);

        $this->jwtService = new AdminJwtService();
        RateLimiter::clear('admin_login_failed:' . md5('admin@example.com') . ':127.0.0.1');
    }

    /** @test */
    public function route_exists_at_exact_uri()
    {
        $response = $this->postJson('/api/v1/admin/auth/login', []);
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function missing_email_returns_422()
    {
        $response = $this->postJson('/api/v1/admin/auth/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
            ])
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function invalid_email_format_returns_422()
    {
        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'invalid-email-format',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function missing_password_returns_422()
    {
        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'admin@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function valid_administrator_login_returns_200_and_tokens()
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('SecretPass123!'),
            'firstname' => 'System',
            'lastname' => 'Admin',
            'status' => null,
        ]);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => '  ADMIN@example.com  ',
            'password' => 'SecretPass123!',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Pragma', 'no-cache');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $response->assertJson([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'token_type' => 'Bearer',
                'expires_in' => 900,
                'admin' => [
                    'id' => $admin->id,
                    'firstname' => 'System',
                    'lastname' => 'Admin',
                    'email' => 'admin@example.com',
                    'role' => 'admin',
                    'permissions' => ['admin.*'],
                ],
            ],
        ]);

        $json = $response->json();
        $accessTokenString = $json['data']['access_token'];
        $this->assertNotEmpty($accessTokenString);

        // Cryptographically validate access JWT signature and constraints via AdminJwtService
        $validatedAccessToken = $this->jwtService->validateAccessToken($accessTokenString);
        $claims = $validatedAccessToken->claims();

        $this->assertEquals((string) $admin->id, $claims->get('sub'));
        $this->assertEquals(config('admin_auth.jwt.issuer'), $claims->get('iss'));
        $this->assertEquals([config('admin_auth.jwt.audience')], $claims->get('aud'));
        $this->assertEquals('access', $claims->get('token_type'));
        $this->assertEquals('admin', $claims->get('role'));
        $this->assertEquals(['admin.*'], $claims->get('permissions'));
        $this->assertNotEmpty($claims->get('jti'));

        // Refresh token must be absent from JSON body
        $this->assertArrayNotHasKey('refresh_token', $json['data']);
        $this->assertArrayNotHasKey('refresh_token', $json);

        // Verify Refresh Cookie
        $response->assertCookie(config('admin_auth.cookie.name'));
        $cookie = $response->getCookie(config('admin_auth.cookie.name'));
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertEquals('/api/v1/admin/auth', $cookie->getPath());
        $this->assertEquals('lax', $cookie->getSameSite());

        // Cryptographically validate refresh JWT signature and constraints via AdminJwtService
        $validatedRefreshToken = $this->jwtService->validateRefreshToken($cookie->getValue());
        $refreshClaims = $validatedRefreshToken->claims();
        $this->assertEquals((string) $admin->id, $refreshClaims->get('sub'));
        $this->assertEquals('refresh', $refreshClaims->get('token_type'));
    }

    /** @test */
    public function unknown_email_returns_401()
    {
        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'unknown@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    /** @test */
    public function wrong_password_returns_401()
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    /** @test */
    public function non_admin_user_returns_401()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'type' => 'user',
        ]);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    /** @test */
    public function suspended_or_disabled_admin_returns_401()
    {
        User::factory()->admin()->create([
            'email' => 'suspended@example.com',
            'password' => Hash::make('password123'),
            'status' => 'suspended',
        ]);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    /** @test */
    public function mfa_enabled_admin_without_code_returns_202_and_no_tokens()
    {
        User::factory()->admin()->withTwoFactor('SECRETKEY123')->create([
            'email' => 'mfa_admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'mfa_admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(202)
            ->assertJson([
                'success' => false,
                'code' => 'MFA_REQUIRED',
                'message' => 'Multi-factor authentication is required.',
                'data' => [
                    'mfa_required' => true,
                ],
            ]);

        $response->assertCookieMissing(config('admin_auth.cookie.name'));
        $this->assertArrayNotHasKey('access_token', $response->json('data') ?? []);
    }

    /** @test */
    public function mfa_enabled_admin_with_correct_code_returns_200()
    {
        User::factory()->admin()->withTwoFactor('SECRETKEY123')->create([
            'email' => 'mfa_admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $mockTfa = Mockery::mock(TwoFactorAuthenticationProvider::class);
        $mockTfa->shouldReceive('verify')->with('SECRETKEY123', '123456')->andReturn(true);
        $this->app->instance(TwoFactorAuthenticationProvider::class, $mockTfa);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'mfa_admin@example.com',
            'password' => 'password123',
            'mfa_code' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful.',
            ]);

        $response->assertCookie(config('admin_auth.cookie.name'));
    }

    /** @test */
    public function mfa_enabled_admin_with_wrong_code_returns_401()
    {
        User::factory()->admin()->withTwoFactor('SECRETKEY123')->create([
            'email' => 'mfa_admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $mockTfa = Mockery::mock(TwoFactorAuthenticationProvider::class);
        $mockTfa->shouldReceive('verify')->with('SECRETKEY123', '999999')->andReturn(false);
        $this->app->instance(TwoFactorAuthenticationProvider::class, $mockTfa);

        $response = $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'mfa_admin@example.com',
            'password' => 'password123',
            'mfa_code' => '999999',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    /** @test */
    public function failed_login_logs_warning_without_sensitive_data()
    {
        Log::spy();

        $this->postJson('/api/v1/admin/auth/login', [
            'email' => 'target_admin@example.com',
            'password' => 'secret_password_123',
            'mfa_code' => '654321',
        ]);

        Log::shouldHaveReceived('warning')
            ->once()
            ->with('Admin authentication failed', Mockery::on(function ($context) {
                return isset($context['email_hash'])
                    && $context['email_hash'] === hash('sha256', 'target_admin@example.com')
                    && !isset($context['password'])
                    && !isset($context['mfa_code'])
                    && !isset($context['access_token'])
                    && !isset($context['refresh_token']);
            }));
    }

    /** @test */
    public function failed_attempts_are_rate_limited_after_five_failures()
    {
        $email = 'limit_admin@example.com';
        $ip = '127.0.0.1';
        $rateLimitKey = 'admin_login_failed:' . md5($email) . ':' . $ip;
        RateLimiter::clear($rateLimitKey);

        // 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $res = $this->postJson('/api/v1/admin/auth/login', [
                'email' => $email,
                'password' => 'wrong_pass',
            ]);
            $this->assertEquals(401, $res->getStatusCode());
        }

        // 6th attempt should be rate limited
        $res = $this->postJson('/api/v1/admin/auth/login', [
            'email' => $email,
            'password' => 'wrong_pass',
        ]);

        $res->assertStatus(429)
            ->assertHeader('Retry-After')
            ->assertJson([
                'success' => false,
                'code' => 'TOO_MANY_LOGIN_ATTEMPTS',
            ]);
    }

    /** @test */
    public function successful_login_clears_failed_attempt_counter()
    {
        $email = 'clear_admin@example.com';
        $ip = '127.0.0.1';
        $rateLimitKey = 'admin_login_failed:' . md5($email) . ':' . $ip;
        RateLimiter::clear($rateLimitKey);

        User::factory()->admin()->create([
            'email' => $email,
            'password' => Hash::make('correct_pass'),
        ]);

        // 2 failed attempts
        for ($i = 0; $i < 2; $i++) {
            $this->postJson('/api/v1/admin/auth/login', [
                'email' => $email,
                'password' => 'wrong_pass',
            ]);
        }

        $this->assertEquals(2, RateLimiter::attempts($rateLimitKey));

        // Successful login
        $this->postJson('/api/v1/admin/auth/login', [
            'email' => $email,
            'password' => 'correct_pass',
        ])->assertStatus(200);

        $this->assertEquals(0, RateLimiter::attempts($rateLimitKey));
    }
}
