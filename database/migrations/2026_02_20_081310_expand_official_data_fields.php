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
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('nama');
            $table->string('provinsi')->nullable()->after('alamat');
            $table->string('kota')->nullable()->after('provinsi');
            $table->string('pimpinan_nama')->nullable()->after('kota');
            $table->string('pimpinan_jabatan')->nullable()->after('pimpinan_nama');
            $table->string('pimpinan_pangkat')->nullable()->after('pimpinan_jabatan');
            $table->string('pimpinan_nip')->nullable()->after('pimpinan_pangkat');
        });

        Schema::table('perizinans', function (Blueprint $table) {
            $table->string('pimpinan_pangkat')->nullable()->after('pimpinan_jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'provinsi', 'kota', 'pimpinan_nama', 'pimpinan_jabatan', 'pimpinan_pangkat', 'pimpinan_nip']);
        });

        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn('pimpinan_pangkat');
        });
    }
};
