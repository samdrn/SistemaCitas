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
        Schema::create('appointment_results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger("patient_id");
            $table->unsignedBigInteger("appointment_id");
            $table->text("diagnostic");
            $table->text("perscription");
            $table->foreign("patient_id")->references("id")->on("patients");
            $table->foreign("appointment_id")->references("id")->on("appointments");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_results');
    }
};
