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
            $table->date('tanggal_terbit')->nullable()->after('nomor_surat');
            $table->string('pimpinan_nama')->nullable()->after('tanggal_terbit');
            $table->string('pimpinan_jabatan')->nullable()->after('pimpinan_nama');
            $table->string('pimpinan_nip')->nullable()->after('pimpinan_jabatan');
            $table->string('stempel_img')->nullable()->after('pimpinan_nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_terbit', 'pimpinan_nama', 'pimpinan_jabatan', 'pimpinan_nip', 'stempel_img']);
        });
    }
};
