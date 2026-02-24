<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('watermark_border_img')->nullable()->after('watermark_size');
        });
    }

    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn('watermark_border_img');
        });
    }
};
