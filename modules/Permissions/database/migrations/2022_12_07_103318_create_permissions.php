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
        if (Schema::hasTable('permissions')) {
            return;
        }

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0)->comment('父级菜单');
            $table->string('permission_name', 50)->comment('菜单名称');
            $table->string('route', 50)->comment('前端路由');
            $table->string('icon', 50)->comment('菜单 icon');
            $table->string('module', 50)->comment('所属模块');
            $table->string('permission_mark', 100)->default('')->comment('权限标识,使用 @ 分割');
            $table->string('component')->comment('组件');
            $table->string('redirect')->nullable()->comment('跳转地址');
            $table->tinyInteger('keepalive')->default(1)->comment('1 缓存 2 不缓存');
            $table->tinyInteger('type')->default(1)->comment('1 目录 2 菜单 3 按钮');
            $table->tinyInteger('hidden')->default(1)->comment('1 显示 2 隐藏');
            $table->integer('sort')->default(1)->comment('排序');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->index(['module', 'permission_mark']);

            $table->engine = 'InnoDB';
            $table->comment('权限表');
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
