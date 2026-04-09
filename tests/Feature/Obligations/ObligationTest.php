<?php

namespace Tests\Feature\Obligations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Operation;
use App\Models\Obligation;

class ObligationTest extends TestCase
{
    public function test_analyst_sees_only_assigned_obligations()
    {
        $analyst = $this->createUser('ANALYST');

        $allowedOperation = Operation::factory()->create();
        $blockedOperation = Operation::factory()->create();

        $this->assignOperationToAnalyst($analyst, $allowedOperation);

        Obligation::factory()->create([
            'operation_id' => $allowedOperation->id
        ]);

        Obligation::factory()->create([
            'operation_id' => $blockedOperation->id
        ]);

        $response = $this->withApiKey($analyst)
            ->getJson('/api/obligations');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertCount(1, $data);
    }
}
