<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\LandingPageSetting;
use App\Models\Lembaga;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
  public function index()
  {
    $data = $this->getCommonData();
    if (is_string($data))
      return $data;

    return view('public.landing', $data);
  }

  public function jenisPerizinan()
  {
    $data = $this->getCommonData();
    if (is_string($data))
      return $data;

    return view('public.jenis_perizinan', $data);
  }

  public function faq()
  {
    $data = $this->getCommonData();
    if (is_string($data))
      return $data;

    return view('public.faq', $data);
  }

  public function lacakStatus()
  {
    $data = $this->getCommonData();
    if (is_string($data))
      return $data;

    return view('public.track_status', $data);
  }

  private function getCommonData()
  {
    $dinas = Dinas::first();

    if (!$dinas) {
      return "Sistem belum siap. Silakan hubungi administrator.";
    }

    $setting = LandingPageSetting::firstOrCreate(
      ['dinas_id' => $dinas->id],
      [
        'hero_title' => 'Layanan Perizinan Digital Terpadu',
        'hero_subtitle' => 'Kelola izin operasional lembaga Anda dengan mudah, cepat, dan transparan melalui portal resmi kami.',
        'support_text' => 'Lembaga yang telah terdaftar dan mempercayakan layanannya kepada kami.',
        'license_title' => 'JENIS IZIN TERSEDIA',
        'license_subtitle' => 'Pilih Jenis Perizinan Anda',
        'license_description' => 'Kami memfasilitasi berbagai tingkatan sertifikasi institusional tergantung pada tahap operasional lembaga Anda.',
        'stats_izin_aktif' => 1240,
        'stats_proses_bulan_ini' => 350,
        'stats_rata_hari' => 14,
        'license_types' => [
          [
            'icon' => 'add_card',
            'title' => 'Izin Operasional Baru',
            'badge' => 'New Applicants',
            'description' => 'Izin standar untuk lembaga PKBM yang baru didirikan.',
            'syarat' => ['NIB', 'AD/ART Lembaga', 'Sertifikat Kelayakan Gedung']
          ],
          [
            'icon' => 'update',
            'title' => 'Perpanjangan Izin',
            'badge' => 'Renewal',
            'description' => 'Layanan perpanjangan untuk izin yang akan segera habis masa berlakunya.',
            'syarat' => ['Salinan Izin Lama', 'Laporan Akademik Tahunan', 'Data Tutor Terbaru']
          ]
        ],
        'faq_title' => 'PUSAT PENGETAHUAN',
        'faq_subtitle' => 'Pertanyaan yang Sering Diajukan',
        'track_placeholder' => 'Masukkan NPSN atau Nama Lembaga...',
        'faq' => [
          [
            'question' => 'Berapa lama waktu proses perizinan baru?',
            'answer' => 'Waktu proses standar adalah 14 hingga 21 hari kerja setelah dokumen dinyatakan lengkap.'
          ],
          [
            'question' => 'Apakah penggunaan portal ini dipungut biaya?',
            'answer' => 'Penggunaan portal ini gratis. Biaya administrasi resmi ditentukan oleh regulasi pemerintah.'
          ]
        ],
        'contact_phone' => '1-800-PKBM-LICENSE',
        'contact_email' => 'support@perizinan.go.id',
        'contact_address' => 'Gedung Dinas Pendidikan, Lt. 2, Jl. Jenderal Sudirman No. 1',
        'footer_description' => 'Portal Informasi Perizinan PKBM Terpadu. Memastikan keunggulan pendidikan melalui layanan perizinan yang transparan dan mudah diakses.',
      ]
    );

    $totalLembaga = Lembaga::count();
    $lembagaLogos = Lembaga::whereNotNull('logo')->latest()->take(3)->get();

    return compact('dinas', 'setting', 'totalLembaga', 'lembagaLogos');
  }
}
