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
        Schema::create('involved_parties', function (Blueprint $table) {
            $table->id();

            // Foreign Key to incidents
            $table->unsignedBigInteger('incident_id');

            // Person Details
            $table->string('full_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('role')->nullable();
            $table->string('license_number')->nullable();
            $table->string('injury_severity')->nullable();
            $table->text('statement')->nullable();

            // Foreign Key Constraint
            $table->foreign('incident_id')
                ->references('id') // FIXED (Laravel uses id, not incident_id)
                ->on('incidents')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('involved_parties');
    }
};
