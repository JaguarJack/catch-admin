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

        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('wx_pc_openid')->after('mobile')->default('')->comment('微信网页授权的 openid');

            $table->string('unionid')->after('wx_pc_openid')->default('')->comment('微信唯一用户标识 unionid');
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
