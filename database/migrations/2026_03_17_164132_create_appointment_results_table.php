<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();

            $table->text('diagnostic');
            $table->text('prescription'); // asi es en ingles, no pers
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_results');
    }
};