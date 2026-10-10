<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_mcp_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('name', 100);
            $table->char('token_hash', 64)->unique();
            $table->unsignedTinyInteger('status')->default(1)->comment('1 启用 2 禁用');
            $table->unsignedInteger('expires_at');
            $table->unsignedInteger('revoked_at')->default(0);
            $table->unsignedInteger('last_used_at')->default(0);
            $table->string('last_used_ip', 45)->default('');
            $table->unsignedInteger('created_at')->default(0);
            $table->unsignedInteger('updated_at')->default(0);
            $table->unsignedInteger('deleted_at')->default(0);
            $table->index(['user_id', 'deleted_at', 'id'], 'system_mcp_tokens_owner_list');
            $table->foreign('user_id')->references('id')->on('admin_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_mcp_tokens');
    }
};
