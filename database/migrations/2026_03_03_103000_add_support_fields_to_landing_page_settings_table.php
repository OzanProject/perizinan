<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->string('support_text')->default('Live support agents available for assistance')->after('hero_subtitle');
      $table->integer('support_agents_count')->default(24)->after('support_text');
    });
  }

  public function down(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->dropColumn(['support_text', 'support_agents_count']);
    });
  }
};
