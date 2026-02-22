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
        Schema::create('cetak_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->string('nama');
            $table->string('paper_size')->default('A4'); // A4, F4, Letter, Custom
            $table->string('orientation')->default('portrait'); // portrait, landscape
            $table->decimal('margin_top', 5, 2)->default(2.0);
            $table->decimal('margin_bottom', 5, 2)->default(2.0);
            $table->decimal('margin_left', 5, 2)->default(2.5);
            $table->decimal('margin_right', 5, 2)->default(2.0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cetak_presets');
    }
};
