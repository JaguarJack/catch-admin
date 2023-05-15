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
        Schema::create('system_dictionary_values', function (Blueprint $table) {
            $table->id();
            $table->integer('dic_id')->comment('字典ID');
            $table->string('label')->comment('值名称');
            $table->tinyInteger('value')->comment('对应值');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态 1 正常 2 禁用');
            $table->string('description', 1000)->comment('描述')->default('');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine='InnoDB';
            $table->comment('字典对应值');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_dictionary_values');
    }
};
