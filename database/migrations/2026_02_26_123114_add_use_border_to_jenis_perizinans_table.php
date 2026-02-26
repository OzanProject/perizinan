<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jenis_perizinans', function (Blueprint $table) {
            $table->boolean('use_border')->default(false)->after('template_html');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_perizinans', function (Blueprint $table) {
            $table->dropColumn('use_border');
        });
    }
};
