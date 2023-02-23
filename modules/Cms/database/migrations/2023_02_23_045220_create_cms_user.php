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
        Schema::create('cms_user', function (Blueprint $table) {
            $table->id();
            $table->string('nickname', 50)->comment('昵称');
            $table->string('password')->comment('密码');
            $table->string('email', 100)->comment('邮箱');
            $table->string('homepage')->comment('主页地址');
            $table->string('active_key')->comment('激活码');
            $table->tinyInteger('status')->default(0)->comment('状态 0 无需激活 1 未激活 2 激活');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('用户管理表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_user');
    }
};
