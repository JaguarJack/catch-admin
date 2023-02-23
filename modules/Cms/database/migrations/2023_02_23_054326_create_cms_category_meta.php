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
        Schema::create('cms_category_meta', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->comment('分类ID');
            $table->string('key')->comment('元数据key');
            $table->string('value')->comment('元数据的值');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('分类元数据');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_category_meta');
    }
};
