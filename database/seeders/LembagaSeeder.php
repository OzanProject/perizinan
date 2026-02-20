<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembaga;
use App\Models\Dinas;

class LembagaSeeder extends Seeder
{
  public function run(): void
  {
    $dinas = Dinas::first();
    if (!$dinas) {
      $dinas = Dinas::create([
        'nama' => 'Dinas Pendidikan Kota',
        'kode_surat' => 'DISDIK',
        'kabupaten' => 'Kota Contoh'
      ]);
    }

    Lembaga::firstOrCreate(['npsn' => '12345678'], [
      'dinas_id' => $dinas->id,
      'nama_lembaga' => 'PKBM Harapan Bangsa',
      'alamat' => 'Jl. Merdeka No. 1'
    ]);

    Lembaga::firstOrCreate(['npsn' => '87654321'], [
      'dinas_id' => $dinas->id,
      'nama_lembaga' => 'PKBM Maju Jaya',
      'alamat' => 'Jl. Keadilan No. 10'
    ]);
  }
}
