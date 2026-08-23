<?php

namespace App\Actions\Perizinan;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePdfAction
{
    public function __construct(protected RenderHtmlAction $renderHtmlAction) {}

    public function handle(Perizinan $perizinan, ?string $paperSize = null, ?string $orientation = null)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
            ->where('is_active', true)->first();

        $paperSize   = strtoupper($paperSize ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4')));
        $orientation = strtolower($orientation ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait')));

        $html = $this->renderHtmlAction->handle($perizinan, $paperSize, $orientation, true);

        $pdf = Pdf::loadHTML($html)
            ->setPaper($this->getPaperDimensions($paperSize), $orientation)
            ->setOptions([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'          => 'Times New Roman',
                'dpi'                  => 96,
            ]);

        $filename = $this->generateStandardFilename($perizinan);
        
        return $pdf->stream($filename . '.pdf');
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

        return [0, 0, min($w, $h), max($w, $h)]; // Selalu portrait
    }

    private function generateStandardFilename(Perizinan $p): string
    {
        $p->load(['lembaga', 'jenisPerizinan']);
        $lembaga = $p->lembaga->nama_lembaga ?? 'Lembaga';
        $jenis   = $p->jenisPerizinan->nama  ?? 'Perizinan';
        $tanggal = date('d-m-Y');
        
        return preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '-', "{$lembaga}-{$jenis}-{$tanggal}"));
    }
}
