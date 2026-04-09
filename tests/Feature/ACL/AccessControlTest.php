<?php

namespace Tests\Feature\ACL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    public function test_analyst_cannot_create_issuer()
    {
        $user = $this->createUser('ANALYST');

        $response = $this->withApiKey($user)
            ->postJson('/api/issuers', [
                'corporate_name' => 'Empresa Teste',
                'cnpj' => '12345678901234'
            ]);

        $response->assertStatus(403);
    }

    public function test_auditor_cannot_create_issuer()
    {
        $user = $this->createUser('AUDITOR');

        $response = $this->withApiKey($user)
            ->postJson('/api/issuers', [
                'corporate_name' => 'Empresa Teste',
                'cnpj' => '12345678901234'
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_issuer()
    {
        $user = $this->createUser('ADMIN');

        $response = $this->withApiKey($user)
            ->postJson('/api/issuers', [
                'corporate_name' => 'Empresa Teste',
                'cnpj' => '12345678901234'
            ]);

        $response->assertStatus(201);
    }
}
