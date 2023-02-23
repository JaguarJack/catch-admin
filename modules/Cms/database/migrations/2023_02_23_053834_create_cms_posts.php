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
        Schema::create('cms_posts', function (Blueprint $table) {
            $table->id();
            $table->string('author')->comment('作者');
            $table->string('title')->comment('标题');
            $table->longText('content')->comment('内容');
            $table->tinyText('excerpt')->comment('摘录');
            $table->tinyInteger('status')->default(1)->comment('文章状态 1 草稿 2 发布');
            $table->tinyInteger('is_can_comment')->default(1)->comment('是否可以评论 1 可以 2 不可以');
            $table->string('password')->comment('文章查看密码');
            $table->integer('order')->default(1)->comment('排序 默认 1');
            $table->integer('user_id')->default(0)->comment('用户ID 0 未知');
            $table->tinyInteger('type')->default(1)->comment('文章类型 1 文章 2 页面');
            $table->integer('comment_count')->default(0)->comment('评论总数');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('文章内容表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_posts');
    }
};
