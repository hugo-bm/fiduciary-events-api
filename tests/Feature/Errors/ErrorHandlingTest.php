<?php

namespace Tests\Feature\Errors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ErrorHandlingTest extends TestCase
{
    public function test_not_found_returns_404()
    {
        $response = $this->getJson('/api/rota-inexistente');

        $response->assertStatus(404);

        $response->assertJson([
            'status' => 'error'
        ]);
    }

    public function test_validation_returns_422()
    {
        $user = $this->createUser('ADMIN');
        $response = $this->withApiKey($user)->postJson('/api/issuers', []);

        $response->assertStatus(422);
    }
}
