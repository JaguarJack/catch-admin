<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('system_sms_code', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->comment('手机号');
            $table->string('code')->comment('短信验证码');
            $table->string('behavior')->comment('短信行为表: 1 login 2 register');
            $table->string('channel')->default('aliyun')->comment('aliyun:阿里云短信,qcloud:腾讯短信');
            $table->integer('status')->default(1)->comment('状态 1 未使用 2 已使用');
            $table->string('expired_at')->default(0)->comment('过期时间');
            $table->createdAt();
            $table->updatedAt();
            $table->comment('短信验证码记录');
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
