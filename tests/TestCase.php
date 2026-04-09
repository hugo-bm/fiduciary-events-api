<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function withApiKey(User $user, string $plainKey = 'test-key')
    {
        return $this->withHeader('X-API-KEY', $plainKey);
    }

    protected function createUser(string $role = 'ADMIN'): User
    {
        return User::factory()->create([
            'role' => $role,
            'is_active' => true,
            'api_key' => bcrypt('test-key'),
        ]);
    }

    protected function assignOperationToAnalyst($analyst, $operation)
    {
        $analyst->operations()->attach($operation->id);
    }

    
}
