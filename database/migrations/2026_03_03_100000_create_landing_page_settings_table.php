<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('landing_page_settings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');

      // Hero Section
      $table->string('hero_title')->default('Layanan Perizinan Digital Terpadu');
      $table->text('hero_subtitle')->nullable();
      $table->string('hero_image')->nullable();

      // Statistik
      $table->unsignedInteger('stats_izin_aktif')->default(0);
      $table->unsignedInteger('stats_proses_bulan_ini')->default(0);
      $table->unsignedInteger('stats_rata_hari')->default(14);

      // Jenis Izin (JSON: [{icon, title, badge, description, syarat:[]}])
      $table->json('license_types')->nullable();

      // FAQ (JSON: [{question, answer}])
      $table->json('faq')->nullable();

      // Kontak
      $table->string('contact_phone')->nullable();
      $table->string('contact_email')->nullable();
      $table->text('contact_address')->nullable();

      // Footer & branding
      $table->text('footer_description')->nullable();
      $table->boolean('show_login_button')->default(true);

      // SEO
      $table->text('meta_description')->nullable();
      $table->string('meta_keywords')->nullable();

      // Tombol hero
      $table->string('cta_primary_text')->default('Ajukan Perizinan');
      $table->string('cta_primary_url')->default('#');
      $table->string('cta_secondary_text')->default('Cek Status');
      $table->string('cta_secondary_url')->default('#status');

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('landing_page_settings');
  }
};
