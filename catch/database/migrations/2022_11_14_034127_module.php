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
        //
        Schema::create(config('catch.module.table_name'), function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->comment('模块标题');

            $table->string('name')->comment('模块名称');

            $table->string('path', 20)->comment('模块目录');

            $table->string('description')->comment('模块描述');

            $table->string('keywords')->comment('模块关键字');

            $table->string('version', 20)->comment('模块版本号')->default('1.0.0');

            $table->boolean('status')->comment('模块状态')->default(1);

            $table->unsignedInteger('created_at')->comment('创建时间')->default(0);

            $table->unsignedInteger('updated_at')->comment('更新时间')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists(config('catch.module.table_name'));
    }
};
