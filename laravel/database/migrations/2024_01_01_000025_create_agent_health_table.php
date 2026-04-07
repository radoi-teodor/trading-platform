<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_health', function (Blueprint $table) {
            $table->id();
            $table->string('agent_type');
            $table->string('agent_id');
            $table->integer('user_id');
            $table->enum('status', ['running', 'stopped', 'error']);
            $table->dateTime('last_heartbeat_at');
            $table->json('metadata');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_health');
    }
};
