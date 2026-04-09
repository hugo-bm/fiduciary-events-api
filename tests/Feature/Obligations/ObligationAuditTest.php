<?php

namespace Tests\Feature\Obligations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Operation;
use App\Models\Obligation;

class ObligationAuditTest extends TestCase
{
    public function test_creating_obligation_generates_audit_log()
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser('ADMIN');

        $operation = Operation::factory()->create();

        $this->withApiKey($user)
            ->postJson('/api/obligations', [
                'operation_id' => $operation->id,
                'title' => 'Test Obligation',
                'due_date' => now()->addDays(10)->toDateString(),
            ]);
        
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'CREATE_OBLIGATION'
        ]);
    }

    public function test_updating_obligation_status_generates_audit_log()
    {

        $user = $this->createUser('ANALYST');

        $operation = Operation::factory()->create();
        $this->assignOperationToAnalyst($user, $operation);

        $obligation = \App\Models\Obligation::factory()->create([
            'operation_id' => $operation->id
        ]);


        $this->withApiKey($user)
            ->patchJson("/api/obligations/{$obligation->id}/status", [
                'status' => 'DELIVERED'
            ]);

        $this->assertDatabaseCount('audit_logs', 1);
    }
}
