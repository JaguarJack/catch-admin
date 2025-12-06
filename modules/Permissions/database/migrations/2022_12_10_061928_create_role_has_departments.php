<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_has_departments', function (Blueprint $table) {
            $table->integer('role_id')->comment('roles primary key');

            $table->integer('department_id')->comment('departments primary key');

            $table->comment('role relate departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
