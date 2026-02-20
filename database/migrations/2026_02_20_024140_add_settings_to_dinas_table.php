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
            $table->string('app_name')->nullable()->after('nama');
            $table->string('logo')->nullable()->after('app_name');
            $table->text('footer_text')->nullable()->after('kabupaten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn(['app_name', 'logo', 'footer_text']);
        });
    }
};
