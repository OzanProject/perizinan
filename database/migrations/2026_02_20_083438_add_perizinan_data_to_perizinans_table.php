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
        Schema::table('perizinans', function (Blueprint $table) {
            $table->json('perizinan_data')->nullable()->after('jenis_perizinan_id');
            $table->text('keterangan_lembaga')->nullable()->after('perizinan_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn(['perizinan_data', 'keterangan_lembaga']);
        });
    }
};
