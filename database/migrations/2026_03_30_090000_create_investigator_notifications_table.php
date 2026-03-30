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
        Schema::create('investigator_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investigator_id');
            $table->unsignedBigInteger('incident_id')->nullable();
            $table->unsignedBigInteger('created_by_investigator_id')->nullable();
            $table->string('type', 50)->default('system');
            $table->string('priority', 20)->default('medium');
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('investigator_id')
                ->references('id')
                ->on('investigators')
                ->cascadeOnDelete();

            $table->foreign('incident_id')
                ->references('id')
                ->on('incidents')
                ->nullOnDelete();

            $table->foreign('created_by_investigator_id')
                ->references('id')
                ->on('investigators')
                ->nullOnDelete();

            $table->index(['investigator_id', 'is_read']);
            $table->index(['investigator_id', 'created_at']);
            $table->index(['type', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigator_notifications');
    }
};
