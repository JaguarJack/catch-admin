<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('字典名称');
            $table->string('key')->comment('字典 key');
            $table->tinyInteger('status')->default(1)->comment('状态 1 启用 2 禁用');
            $table->string('description', 1000)->comment('备注')->default('');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('字段管理');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_dictionary');
    }
};
