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
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->foreignId('lembaga_id')->constrained('lembagas')->onDelete('cascade');
            $table->foreignId('jenis_perizinan_id')->constrained('jenis_perizinans')->onDelete('cascade');
            $table->string('status')->default('draft');
            $table->string('nomor_surat')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('taken_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};
