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
        Schema::create('system_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->default(0)->comment('分类ID');
            $table->string('filename')->comment('文件名');
            $table->string('path')->comment('文件路径');
            $table->string('extension')->comment('文件后缀');
            $table->string('filesize')->comment('文件大小');
            $table->string('mimetype')->comment('文件mimetype');
            $table->string('driver')->comment('上传方式');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine = 'InnoDB';
            $table->comment('附件表');
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
