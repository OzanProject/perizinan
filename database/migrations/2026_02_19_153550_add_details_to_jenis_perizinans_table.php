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
            $table->string('kode')->nullable()->after('nama');
            $table->integer('masa_berlaku_nilai')->default(1)->after('kode');
            $table->string('masa_berlaku_unit')->default('Tahun')->after('masa_berlaku_nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_perizinans', function (Blueprint $table) {
            $table->dropColumn(['kode', 'masa_berlaku_nilai', 'masa_berlaku_unit']);
        });
    }
};
