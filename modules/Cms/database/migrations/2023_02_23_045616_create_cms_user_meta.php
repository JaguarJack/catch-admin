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
        Schema::create('cms_user_meta', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->comment('用户ID');
            $table->string('key')->nullable()->comment('元数据的 key');
            $table->string('value')->nullable()->comment('元数据的值');
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('用户关联的元数据表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_user_meta');
    }
};
