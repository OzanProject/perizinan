<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Perizinan;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->string('qr_token')->unique()->nullable()->after('id');
        });

        // Populate existing records with UUIDs
        foreach (Perizinan::all() as $perizinan) {
            $perizinan->qr_token = Str::uuid()->toString();
            $perizinan->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
