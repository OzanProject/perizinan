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
    Schema::table('lembagas', function (Blueprint $table) {
      $table->string('sk_pendirian')->nullable()->after('alamat');
      $table->date('tanggal_sk_pendirian')->nullable()->after('sk_pendirian');
      $table->string('sk_izin_operasional')->nullable()->after('tanggal_sk_pendirian');
      $table->date('masa_berlaku_izin')->nullable()->after('sk_izin_operasional');
      $table->char('akreditasi', 1)->nullable()->after('masa_berlaku_izin');
      $table->text('visi')->nullable()->after('akreditasi');
      $table->string('telepon')->nullable()->after('visi');
      $table->string('email')->nullable()->after('telepon');
      $table->string('website')->nullable()->after('email');
      $table->string('status_kepemilikan')->nullable()->after('website');
      $table->string('sampul')->nullable()->after('logo');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('lembagas', function (Blueprint $table) {
      $table->dropColumn([
        'sk_pendirian',
        'tanggal_sk_pendirian',
        'sk_izin_operasional',
        'masa_berlaku_izin',
        'akreditasi',
        'visi',
        'telepon',
        'email',
        'website',
        'status_kepemilikan',
        'sampul'
      ]);
    });
  }
};
