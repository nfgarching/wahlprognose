<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('pseudonym', 50);
            // Bürgermeisterwahl: 1 or 2 candidates (2 = predicted runoff)
            $table->foreignId('mayor_candidate_1_id')
                ->nullable()
                ->constrained('candidates')
                ->nullOnDelete();
            $table->foreignId('mayor_candidate_2_id')
                ->nullable()
                ->constrained('candidates')
                ->nullOnDelete();
            // Optional: who wins the runoff
            $table->foreignId('mayor_runoff_winner_id')
                ->nullable()
                ->constrained('candidates')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
