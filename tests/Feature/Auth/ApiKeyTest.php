<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    public function test_request_without_api_key_returns_401()
    {
        $response = $this->postJson('/api/issuers', []);

        $response->assertStatus(401);
    }

    public function test_request_with_invalid_api_key_returns_401()
    {
        $response = $this->withHeader('X-API-KEY', 'invalid-key')
            ->postJson('/api/issuers', []);

        $response->assertStatus(401);
    }

    public function test_request_with_valid_api_key_passes_authentication()
    {
        $user = $this->createUser('ADMIN');

        $response = $this->withApiKey($user)
            ->getJson('/api/issuers');

        // It could be 200 or 403 depending on the route protected by the ACL.
        $this->assertNotEquals(401, $response->status());
    }
}
