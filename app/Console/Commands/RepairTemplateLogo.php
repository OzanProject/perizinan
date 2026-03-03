<?php

namespace App\Console\Commands;

use App\Models\Dinas;
use App\Models\JenisPerizinan;
use Illuminate\Console\Command;

class RepairTemplateLogo extends Command
{
  protected $signature = 'template:repair-logos
                            {--dry-run : Tampilkan apa yang akan diperbaiki tanpa menyimpan perubahan}';

  protected $description = 'Perbaiki placeholder [LOGO_DINAS] di semua template jenis perizinan yang tersimpan sebagai URL img';

  public function handle(): int
  {
    $isDryRun = $this->option('dry-run');
    $this->info($isDryRun ? '🔍 DRY RUN — tidak ada yang disimpan.' : '🔧 Memperbaiki template...');
    $this->newLine();

    $dinasAll = Dinas::whereNotNull('logo')->get();

    if ($dinasAll->isEmpty()) {
      $this->warn('Tidak ada data dinas dengan logo ditemukan.');
      return self::FAILURE;
    }

    $totalFixed = 0;

    foreach ($dinasAll as $dinas) {
      $logoFileName = basename($dinas->logo);
      $this->line("📂 Dinas: <fg=cyan>{$dinas->nama}</> | Logo file: <fg=yellow>{$logoFileName}</>");

      $jenisPerizinans = JenisPerizinan::where('dinas_id', $dinas->id)
        ->whereNotNull('template_html')
        ->get();

      foreach ($jenisPerizinans as $jp) {
        $original = $jp->template_html;
        $repaired = $original;

        // 1. Perbaiki via data-logo marker (dari TinyMCE editor baru)
        $repaired = preg_replace(
          '/<img[^>]*data-logo=["\']1["\'][^>]*\/?>/i',
          '[LOGO_DINAS]',
          $repaired
        );

        // 2. Perbaiki via nama file logo di URL (menangkap http:// maupun https://)
        $repaired = preg_replace(
          '/<img[^>]*src=["\'][^"\']*' . preg_quote($logoFileName, '/') . '[^"\']*["\'][^>]*\/?>/i',
          '[LOGO_DINAS]',
          $repaired
        );

        // 3. Perbaiki via path /storage/ generik (tangkap img URL yang mengandung /storage/)
        //    Hanya jika sudah ada [LOGO_DINAS] sebelumnya tidak ditemukan
        if (!str_contains($repaired, '[LOGO_DINAS]') && str_contains($original, '<img') && str_contains($original, '/storage/')) {
          // Coba tangkap img pertama yang berisi /storage/ sebagai logo kop
          $repaired = preg_replace(
            '/<img[^>]*src=["\'][^"\']*\/storage\/[^"\']*["\'][^>]*\/?>/i',
            '[LOGO_DINAS]',
            $repaired,
            1 // Hanya ganti yang pertama (biasanya logo kop surat)
          );
        }

        if ($repaired !== $original) {
          $this->line("  ✅ <fg=green>Fixed:</> ID:{$jp->id} — {$jp->nama}");
          $totalFixed++;

          if (!$isDryRun) {
            $jp->update(['template_html' => $repaired]);
          }
        } else {
          $hasLogo = str_contains($original, '[LOGO_DINAS]') ? '✅ OK' : '⚠️  Tidak ada logo';
          $this->line("  → ID:{$jp->id} — {$jp->nama} | {$hasLogo}");
        }
      }

      $this->newLine();
    }

    if ($totalFixed === 0) {
      $this->info('✔ Tidak ada template yang perlu diperbaiki.');
    } else {
      $verb = $isDryRun ? 'akan diperbaiki' : 'berhasil diperbaiki';
      $this->info("🎉 Total {$totalFixed} template {$verb}.");
    }

    return self::SUCCESS;
  }
}
