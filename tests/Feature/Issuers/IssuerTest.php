<?php

namespace Tests\Feature\Issuers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Issuer;

class IssuerTest extends TestCase
{
    public function test_admin_can_create_issuer()
    {
        $user = $this->createUser('ADMIN');

        $response = $this->withApiKey($user)
            ->postJson('/api/issuers', [
                'corporate_name' => 'Empresa Teste',
                'cnpj' => '12345678901234'
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('issuers', [
            'corporate_name' => 'Empresa Teste'
        ]);
    }

    public function test_validation_error_returns_422()
    {
        $user = $this->createUser('ADMIN');

        $response = $this->withApiKey($user)
            ->postJson('/api/issuers', []);

        $response->assertStatus(422);
    }

    public function test_can_list_issuers()
    {
        $user = $this->createUser('ADMIN');

        Issuer::factory()->count(3)->create();

        $response = $this->withApiKey($user)
            ->getJson('/api/issuers');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
}
