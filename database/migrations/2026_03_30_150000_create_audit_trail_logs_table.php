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
        Schema::create('audit_trail_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id')->nullable();
            $table->unsignedBigInteger('investigator_id')->nullable();
            $table->string('action_type', 50)->default('system');
            $table->text('action_performed');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('incident_id')
                ->references('id')
                ->on('incidents')
                ->nullOnDelete();

            $table->foreign('investigator_id')
                ->references('id')
                ->on('investigators')
                ->nullOnDelete();

            $table->index(['incident_id', 'created_at']);
            $table->index(['investigator_id', 'created_at']);
            $table->index(['action_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trail_logs');
    }
};
