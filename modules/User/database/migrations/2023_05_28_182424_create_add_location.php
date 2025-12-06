<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('log_login', function (Blueprint $table) {
            $table->string('location')->after('login_ip')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
