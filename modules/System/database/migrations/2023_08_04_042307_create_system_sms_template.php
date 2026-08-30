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
    public function up()
    {
        if (Schema::hasTable('system_sms_template')) {
            return;
        }

        Schema::create('system_sms_template', function (Blueprint $table) {
            $table->id();
            $table->string('channel')->default('aliyun')->comment('aliyun:阿里云短信,qcloud:腾讯短信');
            $table->string('template_id')->default('')->comment('模版ID');
            $table->string('content', 2000)->default('')->comment('模版内容');
            $table->string('variables')->default('')->comment('模版变量');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine = 'InnoDB';
            $table->comment('短信模版');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_sms_template');
    }
};
