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
        Schema::create('system_mcp_token_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('token_id');
            $table->string('tool_name', 100);
            $table->string('ip', 45);
            $table->unsignedTinyInteger('result')->comment('1 成功 2 失败');
            $table->unsignedInteger('created_at');
            $table->index(['token_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_mcp_token_logs');
    }
};
