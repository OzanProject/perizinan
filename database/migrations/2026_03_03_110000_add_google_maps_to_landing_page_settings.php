<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->text('google_maps_embed')->nullable()->after('contact_address');
    });
  }

  public function down(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->dropColumn('google_maps_embed');
    });
  }
};
