<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up()
    {
        Schema::create('schemas', function (Blueprint $table) {
            $table->increments('id');

            $table->string('module')->nullable(false)->comment('模块名称');

            $table->string('name')->nullable(false)->comment('schema 名称');

            $table->string('columns', 2000)->nullable(false)->comment('字段');

            $table->boolean('is_soft_delete')->default(1)->comment('1 是 2 否');

            $table->createdAt();

            $table->updatedAt();

            $table->deletedAt();
        });
    }
};
