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
        Schema::create('cms_links', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('链接名称');
            $table->string('url')->comment('链接的 URL');
            $table->string('image')->comment('链接图片');
            $table->tinyInteger('is_target')->default(1)->comment('打开方式 1 本窗口打开 2 新窗口打开');
            $table->tinyText('description')->comment('描述');
            $table->tinyInteger('is_visible')->default(1)->comment('是否可见 1 不可见 2 可见');
            $table->integer('rating')->comment('评分等级');
            $table->string('rel')->comment('友情链接');
            $table->string('rel_notes', 1000)->comment('友情链接注释');
            $table->string('rss')->comment('rss 链接地址');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('链接表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_links');
    }
};
