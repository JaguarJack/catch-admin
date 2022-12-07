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
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('permission_name', 30)->comment('菜单名称');
            $table->integer('parent_id')->default('1')->comment('父级菜单');
            $table->string('route', 50)->comment('路由');
            $table->string('icon', 50)->comment('菜单 icon');
            $table->string('module', 50)->comment('所属模块');
            $table->string('permission_mark')->comment('权限标识');
            $table->string('component')->comment('组件');
            $table->string('redirect')->comment('组件跳转');
            $table->tinyInteger('keepalive')->default('1')->comment('1 缓存 2 不缓存');
            $table->tinyInteger('type')->default('1')->comment('1 菜单 2 按钮');
            $table->tinyInteger('hidden')->default('1')->comment('1 显示 2 隐藏');
            $table->integer('sort')->default('1')->comment('排序');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine = 'InnoDB';
            $table->comment('权限模块');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
