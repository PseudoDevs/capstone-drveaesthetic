<?php

namespace Tests\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class SecurityTestSuite extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /** @test */
    public function rate_limiting_blocks_excessive_requests()
    {
        // Test login rate limiting
        for ($i = 0; $i < 15; $i++) {
            $response = $this->postJson('/api/client/auth/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword'
            ]);
            
            if ($i >= 10) {
                $this->assertEquals(429, $response->status());
            }
        }
    }

    /** @test */
    public function sql_injection_attempts_are_blocked()
    {
        $injectionAttempts = [
            "admin' OR '1'='1",
            "'; DROP TABLE users; --",
            "' UNION SELECT * FROM users --",
            "admin'/**/OR/**/1=1--"
        ];

        foreach ($injectionAttempts as $injection) {
            $response = $this->postJson('/api/client/auth/login', [
                'email' => $injection,
                'password' => 'anything'
            ]);

            $this->assertEquals(401, $response->status());
            $this->assertFalse($response->json('success'));
        }
    }

    /** @test */
    public function xss_attempts_are_sanitized()
    {
        $xssAttempts = [
            '<script>alert("xss")</script>',
            '"><script>alert("xss")</script>',
            'javascript:alert("xss")',
            '<img src=x onerror=alert("xss")>'
        ];

        foreach ($xssAttempts as $xss) {
            $response = $this->postJson('/api/client/auth/login', [
                'email' => $xss,
                'password' => 'test'
            ]);

            $this->assertEquals(401, $response->status());
            $this->assertStringNotContainsString('<script>', $response->getContent());
        }
    }

    /** @test */
    public function unauthorized_access_is_blocked()
    {
        $protectedEndpoints = [
            '/api/client/chats',
            '/api/client/users',
            '/api/client/appointments',
            '/api/client/medical-certificates'
        ];

        foreach ($protectedEndpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $this->assertEquals(401, $response->status());
        }
    }

    /** @test */
    public function large_requests_are_rejected()
    {
        $largePayload = str_repeat('x', 2 * 1024 * 1024); // 2MB

        $response = $this->postJson('/api/client/auth/login', [
            'email' => 'test@example.com',
            'password' => $largePayload
        ]);

        $this->assertEquals(413, $response->status());
    }

    /** @test */
    public function security_headers_are_present()
    {
        $response = $this->getJson('/api/client/auth/login');

        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertEquals('DENY', $response->headers->get('X-Frame-Options'));
        $this->assertEquals('1; mode=block', $response->headers->get('X-XSS-Protection'));
        $this->assertEquals('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    /** @test */
    public function session_cookies_are_secure()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->getJson('/api/client/auth/profile');
        
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === config('session.cookie')) {
                $this->assertTrue($cookie->isHttpOnly());
                $this->assertTrue($cookie->isSecure());
                $this->assertEquals('strict', $cookie->getSameSite());
            }
        }
    }

    /** @test */
    public function csrf_protection_works()
    {
        // Test CSRF protection on web routes
        $response = $this->post('/client/appointments', [
            'service_id' => 1,
            'appointment_date' => '2024-01-01'
        ]);

        $this->assertEquals(419, $response->status()); // CSRF token mismatch
    }

    /** @test */
    public function file_upload_security_works()
    {
        $user = User::factory()->create();
        
        // Test file type validation
        $response = $this->actingAs($user)->postJson('/api/client/users/1/upload-avatar', [
            'avatar' => 'test.txt' // Invalid file type
        ]);

        $this->assertEquals(422, $response->status());
        $this->assertArrayHasKey('avatar', $response->json('errors'));
    }

    /** @test */
    public function password_requirements_are_enforced()
    {
        $weakPasswords = [
            '123',
            'password',
            '12345678',
            'qwerty'
        ];

        foreach ($weakPasswords as $password) {
            $response = $this->postJson('/api/client/auth/register', [
                'name' => 'Test User',
                'email' => $this->faker->unique()->safeEmail,
                'password' => $password,
                'password_confirmation' => $password
            ]);

            $this->assertEquals(422, $response->status());
            $this->assertArrayHasKey('password', $response->json('errors'));
        }
    }

    /** @test */
    public function rate_limiting_resets_after_timeout()
    {
        // Hit rate limit
        for ($i = 0; $i < 15; $i++) {
            $this->postJson('/api/client/auth/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword'
            ]);
        }

        // Wait for rate limit to reset (in real scenario, this would be 60 seconds)
        // For testing, we'll clear the rate limiter
        RateLimiter::clear('login');
        Cache::flush();

        // Should work again
        $response = $this->postJson('/api/client/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $this->assertEquals(401, $response->status()); // Not 429
    }

    /** @test */
    public function debug_information_is_not_exposed()
    {
        // Test that debug information is not exposed in production
        config(['app.debug' => false]);
        
        $response = $this->postJson('/api/client/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        $this->assertStringNotContainsString('stack trace', $response->getContent());
        $this->assertStringNotContainsString('file:', $response->getContent());
        $this->assertStringNotContainsString('line:', $response->getContent());
    }

    /** @test */
    public function sensitive_data_is_not_logged()
    {
        // Test that sensitive data is not logged
        $response = $this->postJson('/api/client/auth/login', [
            'email' => 'test@example.com',
            'password' => 'secretpassword123'
        ]);

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        
        $this->assertStringNotContainsString('secretpassword123', $logContent);
        $this->assertStringNotContainsString('password', $logContent);
    }
}
