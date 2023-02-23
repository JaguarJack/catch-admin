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
        Schema::create('cms_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('name')->comment('名称');
            $table->string('slug')->comment('缩略名');
            $table->integer('order')->default(1)->comment('排序 默认 1');
            $table->integer('post_count')->default(0)->comment('文章数量');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('分类');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_category');
    }
};
