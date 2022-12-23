<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_operate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module', 50)->comment('操作');
            $table->string('action', 50)->comment('操作');
            $table->text('params')->comment('参数');
            $table->string('ip')->comment('ip 地址');
            $table->string('http_method', 10)->comment('http 请求方式');
            $table->smallInteger('http_code')->comment('http status code');
            $table->unsignedInteger('start_at')->comment('请求开始时间');
            $table->smallInteger('time_taken')->comment('请求消耗时间/ms');
            $table->creatorId();
            $table->createdAt();

            $table->engine = 'InnoDB';
            $table->comment('操作日志');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_operate');
    }
};
