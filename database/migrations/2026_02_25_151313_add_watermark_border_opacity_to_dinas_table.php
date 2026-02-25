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
            $table->float('watermark_border_opacity')->default(0.2)->after('watermark_opacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn('watermark_border_opacity');
        });
    }
};
