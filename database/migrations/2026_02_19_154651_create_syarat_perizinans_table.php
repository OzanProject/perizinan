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
        Schema::create('syarat_perizinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_perizinan_id')->constrained('jenis_perizinans')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_file', ['pdf', 'image', 'all'])->default('pdf');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarat_perizinans');
    }
};
