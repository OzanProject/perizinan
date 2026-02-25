<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentRenderService
{
    public function renderHtml(Perizinan $p, $preset = null): string
    {
        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        // Use the user-designed template from the template editor
        $body = $p->replaceVariables();

        $pageCss = $this->buildPageCss($preset);

        return '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>
          ' . $pageCss . '
          body { font-family: DejaVu Sans, sans-serif; font-size: 10.5pt; line-height: 1.1; color: #000; margin: 0; padding: 0; }
          table { border-collapse: collapse; page-break-inside: avoid; }
          td { vertical-align: top; padding: 1px 0; }
          img { max-width: 100%; page-break-inside: avoid; }
          .signature-block { page-break-inside: avoid; breaks-inside: avoid; margin-top: 5px; }
        </style>
        </head><body>' . $body . '</body></html>';
    }

    public function generatePdf(Perizinan $p)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $p->dinas_id)
            ->where('is_active', true)
            ->first();

        $html = $this->renderHtml($p, $preset);

        $paperSize = $preset->paper_size ?? 'a4';
        $orientation = $preset->orientation ?? 'portrait';

        // DomPDF doesn't natively support F4. We define it manually (210mm x 330mm in points).
        // 1mm = 2.83465 points
        if (strtoupper($paperSize) === 'F4') {
            $paperSize = [0, 0, 595.28, 935.43]; // 210mm x 330mm
        }

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSize, $orientation)
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        $filename = $this->generateStandardFilename($p) . '.pdf';

        return $pdf->stream($filename);
    }

    public function generateStandardFilename(Perizinan $p): string
    {
        $p->load(['lembaga', 'jenisPerizinan']);

        $lembaga = $p->lembaga->nama_lembaga ?? 'Lembaga';
        $jenis = $p->jenisPerizinan->nama ?? 'Perizinan';
        $tanggal = date('d-m-Y');

        $name = "{$lembaga}-{$jenis}-{$tanggal}";

        // Sanitize: replace spaces with dash and remove special characters
        $name = str_replace(' ', '-', $name);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);

        return $name;
    }

    public function storePermanentPdf(Perizinan $perizinan): string
    {
        $html = $this->renderHtml($perizinan);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $folder = 'issued_pdfs/' . date('Y/m');
        if (!Storage::disk('local')->exists($folder)) {
            Storage::disk('local')->makeDirectory($folder);
        }

        $filename = "{$perizinan->id}_" . time() . ".pdf";
        $path = "{$folder}/{$filename}";

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    private function buildPageCss($preset): string
    {
        $size = 'A4 portrait';
        $margin = '10mm 10mm 10mm 10mm';

        if ($preset) {
            $paperSize = strtoupper($preset->paper_size ?: 'a4');
            $orientation = strtolower($preset->orientation ?: 'portrait');

            if ($paperSize === 'F4') {
                $size = "210mm 330mm {$orientation}";
            } else {
                $size = "{$paperSize} {$orientation}";
            }

            $mt = $preset->margin_top ?: 20;
            $mr = $preset->margin_right ?: 20;
            $mb = $preset->margin_bottom ?: 20;
            $ml = $preset->margin_left ?: 20;

            // Convert cm to mm if needed (user inputs cm in UI)
            // But UI says margin (cm) so we should multiply by 10 if we want mm
            // Currently preset stores it as cm in input-margin-top etc.
            // Let's check how it's stored in database.
            $margin = "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
        }

        return "
        @page {
            size: {$size};
            margin: {$margin};
        }
        ";
    }

    private function toBase64Public(?string $path): ?string
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        $fullPath = Storage::disk('public')->path($path);
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = base64_encode(file_get_contents($fullPath));

        return "data:image/{$type};base64,{$data}";
    }
}