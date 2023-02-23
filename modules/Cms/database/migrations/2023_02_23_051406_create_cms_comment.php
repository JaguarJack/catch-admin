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
        Schema::create('cms_comment', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->comment('父级ID');
            $table->integer('user_id')->default(0)->comment('用户ID，不一定存在');
            $table->integer('post_id')->default(0)->comment('文章ID');
            $table->string('author')->nullable()->comment('作者');
            $table->string('author_email')->comment('作者邮箱');
            $table->string('author_homepage')->comment('作者的主页');
            $table->string('author_ip')->comment('ip地址');
            $table->text('content')->comment('评论内容');
            $table->tinyInteger('is_approved')->default(1)->comment('1 未批准 2 批准');
            $table->string('user_agent')->comment('评论者的USER AGENT');
            $table->string('type')->default(1)->comment('评论类型 1 普通 2 回复');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('评论表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_comment');
    }
};
