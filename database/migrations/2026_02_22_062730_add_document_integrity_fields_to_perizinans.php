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
            $table->string('document_hash')->nullable()->after('snapshot_html');
            $table->string('pdf_path')->nullable()->after('document_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn(['document_hash', 'pdf_path']);
        });
    }
};
