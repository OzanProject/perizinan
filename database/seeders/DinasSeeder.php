<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;

class DinasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Dinas::updateOrCreate(
      ['nama' => 'Dinas Pendidikan Kabupaten Garut'],
      [
        'app_name' => 'Si-Perizinan Garut',
        'kode_surat' => '420/DIK',
        'alamat' => 'Jl. Pembangunan No. 123, Garut, Jawa Barat',
        'kabupaten' => 'Garut',
        'provinsi' => 'Jawa Barat',
        'pimpinan_nama' => 'Dr. H. Ade Hendarsyah, M.M.',
        'pimpinan_jabatan' => 'Kepala Dinas Pendidikan',
        'pimpinan_pangkat' => 'Pembina Utama Muda',
        'pimpinan_nip' => '197001011995011001',
        'footer_text' => 'Dinas Pendidikan Kabupaten Garut - Melayani dengan Hati',
        'watermark_enabled' => true,
        'watermark_opacity' => 0.1,
        'watermark_border_opacity' => 0.2,
        'watermark_size' => 400,
      ]
    );
  }
}
