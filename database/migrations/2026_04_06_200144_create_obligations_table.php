<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('obligations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->date('due_date');

            $table->enum('status', ['PENDING', 'DELIVERED', 'CANCELED'])
                ->default('PENDING');

            $table->date('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obligations');
    }
};
