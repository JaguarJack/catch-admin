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
        Schema::create('system_attachment_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('title')->comment('分类名称');
            $table->creatorId();
            $table->createdAt();
            $table->updatedAt();
            $table->deletedAt();
            $table->engine = 'InnoDB';
            $table->comment('附件分类表');
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
