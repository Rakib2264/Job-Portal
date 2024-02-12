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
        Schema::table('jobs', function (Blueprint $table) {
            $table->integer('status')->default(1)->comment('1 active o inactive')->nullable()->after('company_website');
            $table->integer('isfeatured')->default(0)->nullable()->after('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('status')->default(1)->comment('1 active o inactive')->nullable();
            $table->dropColumn('isfeatured')->default(0)->nullable();

        });
    }
};
