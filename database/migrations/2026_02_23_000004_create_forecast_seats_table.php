<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forecast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('seats')->default(0);
            $table->timestamps();

            $table->unique(['forecast_id', 'party_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_seats');
    }
};
