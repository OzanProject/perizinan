<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->string('social_facebook')->nullable()->after('google_maps_embed');
      $table->string('social_instagram')->nullable()->after('social_facebook');
      $table->string('social_twitter')->nullable()->after('social_instagram');
      $table->string('social_youtube')->nullable()->after('social_twitter');
    });
  }

  public function down(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->dropColumn(['social_facebook', 'social_instagram', 'social_twitter', 'social_youtube']);
    });
  }
};
