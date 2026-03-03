<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->string('license_title')->default('Jenis Perizinan Tersedia')->after('support_agents_count');
      $table->string('license_subtitle')->default('Pilih Jenis Perizinan Anda')->after('license_title');
      $table->text('license_description')->nullable()->after('license_subtitle');

      $table->string('faq_title')->default('Pusat Pengetahuan')->after('license_types');
      $table->string('faq_subtitle')->default('Pertanyaan yang Sering Diajukan')->after('faq_title');
    });
  }

  public function down(): void
  {
    Schema::table('landing_page_settings', function (Blueprint $table) {
      $table->dropColumn(['license_title', 'license_subtitle', 'license_description', 'faq_title', 'faq_subtitle']);
    });
  }
};
