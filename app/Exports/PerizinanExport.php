<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerizinanExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
  protected array $rows;

  public function __construct(array $rows)
  {
    $this->rows = $rows;
  }

  public function array(): array
  {
    return $this->rows;
  }

  public function title(): string
  {
    return 'Data Perizinan';
  }

  public function styles(Worksheet $sheet): array
  {
    // Find header rows (INFORMASI PERIZINAN, DATA LEMBAGA, etc.) and bold them
    $styles = [];
    foreach ($this->rows as $index => $row) {
      $rowNum = $index + 1;
      if (isset($row[0]) && $row[0] !== '' && ($row[1] ?? '') === '') {
        // Header row - bold and colored
        $styles[$rowNum] = [
          'font' => ['bold' => true, 'size' => 12],
        ];
      }
    }

    // Also bold column A for all data labels
    $styles['A'] = ['font' => ['bold' => true]];

    return $styles;
  }
}
