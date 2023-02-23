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
        Schema::create('cms_comment_mata', function (Blueprint $table) {
            $table->id();
            $table->integer('comment_id')->comment('评论ID');
            $table->string('key')->comment('评论元数据 KEY');
            $table->string('value')->comment('评论元数据 Value');
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('评论元数据表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_comment_mata');
    }
};
