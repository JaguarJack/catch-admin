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
        if (Schema::hasTable('roles')) {
            return ;
        }

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_name', 30)->comment('角色名称');
            $table->string('identify', 30)->nullable()->comment('角色的标识，用英文表示');
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('description')->nullable()->comment('角色描述');
            $table->smallInteger('data_range')->default(0)->comment('1 全部数据 2 自定义数据 3 仅本人数据 4 部门数据 5 部门及以下数据');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine = 'InnoDB';
            $table->comment('角色表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
