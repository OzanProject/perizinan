<?php

namespace App\Actions\Perizinan;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class StorePermanentPdfAction
{
    public function __construct(protected RenderHtmlAction $renderHtmlAction) {}

    public function handle(Perizinan $perizinan): string
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
            ->where('is_active', true)->first();

        $paperSize   = strtoupper($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4'));
        $orientation = strtolower($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait'));

        $html = $this->renderHtmlAction->handle($perizinan, $paperSize, $orientation, true);

        $pdf = Pdf::loadHTML($html)
            ->setPaper($this->getPaperDimensions($paperSize), $orientation)
            ->setOptions([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'          => 'Times New Roman',
                'dpi'                  => 96,
            ]);

        $folder = 'issued_pdfs/' . date('Y/m');
        if (!Storage::disk('local')->exists($folder)) {
            Storage::disk('local')->makeDirectory($folder);
        }

        $filename = "{$perizinan->id}_" . time() . ".pdf";
        $path     = "{$folder}/{$filename}";
        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    private function getPaperDimensions(string $paperSize): array
    {
        $sizes = [
            'A4'     => ['w_mm' => 210.0, 'h_mm' => 297.0],
            'F4'     => ['w_mm' => 215.0, 'h_mm' => 330.0],
            'A3'     => ['w_mm' => 297.0, 'h_mm' => 420.0],
            'LETTER' => ['w_mm' => 215.9, 'h_mm' => 279.4],
        ];

        $key  = strtoupper($paperSize);
        $dims = $sizes[$key] ?? $sizes['A4'];

        $mmToPt = 2.834645;
        $w = $dims['w_mm'] * $mmToPt;
        $h = $dims['h_mm'] * $mmToPt;

        return [0, 0, min($w, $h), max($w, $h)];
    }
}
