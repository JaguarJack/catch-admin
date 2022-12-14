<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('jobs')) {
            return ;
        }

        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_name', 50)->comment('岗位名称');
            $table->string('coding', 30)->nullable()->comment('创建人ID');
            $table->tinyInteger('status')->default('1')->comment('1 正常 2 停用');
            $table->integer('sort')->default('1')->comment('排序');
            $table->string('description', 1000)->nullable()->comment('岗位描述');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();

            $table->engine = 'InnoDB';
            $table->comment('岗位表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
