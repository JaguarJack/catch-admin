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
    public function up(): void
    {

        Schema::create('schema_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id');
            $table->text('controller_path')->nullable()->comment('控制器文件路径');
            $table->text('model_path')->nullable()->comment('模型文件路径');
            $table->text('request_path')->nullable()->comment('请求文件路径');
            $table->text('table_path')->nullable()->comment('vue table 文件路径');
            $table->text('form_path')->nullable()->comment('vue form 文件路径');
            $table->text('controller_file')->nullable()->comment('控制器文件内容');
            $table->text('model_file')->nullable()->comment('模型文件内容');
            $table->text('request_file')->nullable()->comment('请求文件内容');
            $table->text('table_file')->nullable()->comment('vue table 文件内容');
            $table->text('form_file')->nullable()->comment('vue form 文件内容');
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();
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
