<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LembagaStatsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function collection()
  {
    return $this->data;
  }

  public function headings(): array
  {
    return [
      'No',
      'Nama Lembaga',
      'Jenjang',
      'Total Pengajuan',
      'Selesai',
      'Dalam Proses',
      'Persentase Kelulusan (%)',
    ];
  }

  public function map($lembaga): array
  {
    static $index = 0;
    $index++;

    $persentase = $lembaga->total_pengajuan > 0
      ? round(($lembaga->selesai / $lembaga->total_pengajuan) * 100, 1)
      : 0;

    return [
      $index,
      $lembaga->nama_lembaga,
      strtoupper($lembaga->jenjang),
      $lembaga->total_pengajuan,
      $lembaga->selesai,
      $lembaga->proses,
      $persentase,
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }
}
