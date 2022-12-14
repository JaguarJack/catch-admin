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
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('username')->comment('昵称');

            $table->string('password')->comment('密码');

            $table->string('email')->comment('邮箱');

            $table->string('avatar')->nullable()->comment('头像');

            $table->string('remember_token', 1000)->nullable()->comment('token');

            $table->integer('department_id')->default(0)->comment('部门ID');

            $table->integer('creator_id')->default(0);

            $table->status();

            $table->string('login_ip')->nullable()->comment('登录IP');

            $table->integer('login_at')->default(0)->comment('登录时间');

            $table->createdAt();

            $table->updatedAt();

            $table->deletedAt();

            $table->comment('用户表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
};
