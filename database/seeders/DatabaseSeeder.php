<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Issuer;
use App\Models\Operation;
use App\Models\Obligation;
use App\Enums\UserRoleEnum;
use App\Enums\ObligationStatusEnum;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // ADMIN
        $admin = User::factory()->create([
            'name' => 'Admin',
            'role' => UserRoleEnum::ADMIN->value,
            'api_key' => hash('sha256', 'admin-key'),
        ]);

        // AUDITORS
        $auditors = User::factory(2)->create([
            'role' => UserRoleEnum::AUDITOR->value,
        ]);

        // ANALYSTS
        $analysts = User::factory(5)->create([
            'role' => UserRoleEnum::ANALYST->value,
        ]);

        // ISSUERS
        $issuers = Issuer::factory(10)->create();

        $operations = collect();

        // OPERATIONS (3 by issuer)
        foreach ($issuers as $issuer) {
            $ops = Operation::factory(3)->create([
                'issuer_id' => $issuer->id,
            ]);

            $operations = $operations->merge($ops);
        }

        // LINK ANALYST ↔ OPERATIONS
        foreach ($operations as $index => $operation) {
            $analyst = $analysts[$index % $analysts->count()];
            $operation->analysts()->attach($analyst->id);
        }

        // OBLIGATIONS (10 by operation)
        foreach ($operations as $operation) {
            Obligation::factory(10)->create([
                'operation_id' => $operation->id,
                'status' => fake()->randomElement([
                    ObligationStatusEnum::PENDING->value,
                    ObligationStatusEnum::DELIVERED->value,
                ]),
                'due_date' => now()->addDays(rand(-10, 30)),
            ]);
        }
    
    }
}
