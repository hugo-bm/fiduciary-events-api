<?php

namespace Tests\Feature\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Obligation;

class DashboardTest extends TestCase
{
   public function test_dashboard_summary_counts_risk_levels()
    {
        $this->withoutExceptionHandling();

        $user = $this->createUser('ADMIN');
        Obligation::factory()->create([
            'due_date' => now()->subDay(), // critical
            'status' => 'PENDING'
        ]);

        Obligation::factory()->create([
            'due_date' => now()->addDays(5), // warning
            'status' => 'PENDING'
        ]);

        Obligation::factory()->create([
            'due_date' => now()->addDays(30), // normal
            'status' => 'PENDING'
        ]);

        Obligation::factory()->create([
            'due_date' => now()->addDays(30), // normal
            'status' => 'PENDING'
        ]);

        $response = $this->withApiKey($user)->getJson('/api/dashboard/summary');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => ['critical', 'warning', 'normal']
        ]);

        $response->assertJsonFragment(['data' => ['critical' => 1, 'warning' => 1, 'normal' => 2 ]]);
    }
}
