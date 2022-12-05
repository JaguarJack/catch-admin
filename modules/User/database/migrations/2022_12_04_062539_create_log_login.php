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
        Schema::create('log_login', function (Blueprint $table) {
            $table->increments('id');

            $table->string('account')->comment('登录账户');

            $table->string('login_ip')->comment('登录的IP');

            $table->string('browser')->comment('浏览器');

            $table->string('platform')->comment('平台');

            $table->integer('login_at')->comment('平台');

            $table->status();
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
