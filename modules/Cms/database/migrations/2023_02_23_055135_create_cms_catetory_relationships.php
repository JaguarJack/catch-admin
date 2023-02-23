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
        Schema::create('cms_catetory_relationships', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->comment('分类ID');
            $table->integer('object_id')->comment('文章ID/链接ID');
            $table->tinyInteger('type')->default(1)->comment('类型 1 文章 2 链接');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('分类关系');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_catetory_relationships');
    }
};
