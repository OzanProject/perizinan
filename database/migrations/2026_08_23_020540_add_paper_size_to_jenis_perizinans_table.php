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
        Schema::table('jenis_perizinans', function (Blueprint $table) {
            $table->string('paper_size')->nullable()->after('use_border');
            $table->string('orientation')->nullable()->after('paper_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_perizinans', function (Blueprint $table) {
            $table->dropColumn(['paper_size', 'orientation']);
        });
    }
};
