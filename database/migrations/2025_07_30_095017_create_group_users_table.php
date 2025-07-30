<?php

declare(strict_types=1);

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
        Schema::create('group_users', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['approved', 'pending']);
            $table->enum('role', ['admin', 'user']);
            $table->string('token', 1024)->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('token_used')->nullable();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_users');
    }
};
