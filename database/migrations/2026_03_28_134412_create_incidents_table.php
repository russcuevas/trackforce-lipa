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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            // Report Info
            $table->string('report_number', 20)->unique(); // TFL-YYYY-XXXX
            $table->string('incident_type');

            // Location & Environment
            $table->string('location_name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('road_condition')->nullable();
            $table->string('weather_condition')->nullable();

            // Reporter Info
            $table->string('reporter_name')->nullable();
            $table->string('reporter_contact', 20)->nullable();
            $table->string('reporter_email', 100)->nullable();
            $table->text('reporter_address')->nullable();

            // Status & Analytics
            $table->string('status')->nullable();
            $table->string('resolved_statement')->nullable();
            $table->timestamp('time_reported')->useCurrent();
            $table->dateTime('time_documented')->nullable();
            $table->dateTime('time_completed')->nullable();
            $table->string('otp', 20);
            $table->boolean('is_verified')->default(false);

            // Assignment
            $table->unsignedBigInteger('assigned_investigator_id')->nullable();
            $table->foreign('assigned_investigator_id')
                ->references('id')
                ->on('investigators')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
