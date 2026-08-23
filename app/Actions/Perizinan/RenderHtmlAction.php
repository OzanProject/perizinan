<?php

namespace App\Actions\Perizinan;

use App\Models\CetakPreset;
use App\Models\Perizinan;
use Illuminate\Support\Facades\Storage;

class RenderHtmlAction
{
    /**
     * Ukuran kertas yang didukung (dalam milimeter).
     * Catatan: konstanta ini didefinisikan untuk referensi/dokumentasi ukuran kertas,
     * meski perhitungan tinggi aktual saat render tetap memakai nilai hardcode
     * di buildPageCss() / patchSnapshotOrientation() (tidak diubah agar logika tetap sama).
     */
    private const PAPER_SIZES = [
        'A4'     => ['w_mm' => 210.0, 'h_mm' => 297.0],
        'F4'     => ['w_mm' => 215.0, 'h_mm' => 330.0],
        'A3'     => ['w_mm' => 297.0, 'h_mm' => 420.0],
        'LETTER' => ['w_mm' => 215.9, 'h_mm' => 279.4],
    ];

    /* =====================================================================
     |  ENTRY POINT
     |===================================================================== */

    public function handle(
        Perizinan $perizinan,
        ?string $paperSize = null,
        ?string $orientation = null,
        bool $forPdf = false
    ): string {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');

        $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);

        $preset = CetakPreset::where('dinas_id', $perizinan->dinas_id)
            ->where('is_active', true)
            ->first();

        $paperSize   = strtoupper($paperSize ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4')));
        $orientation = strtolower($orientation ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait')));

        $snapshot = $perizinan->snapshot_html;

        // Kasus 1: snapshot sudah berupa HTML utuh (dari sistem lama) → patch ukurannya saja
        if (!empty($snapshot) && stripos($snapshot, '<html') !== false) {
            return $this->renderFromLegacySnapshot($perizinan, $snapshot, $paperSize, $orientation);
        }

        // Kasus 2: snapshot berupa raw body (sistem baru), atau belum ada snapshot sama sekali
        return $this->renderFromBody($perizinan, $snapshot, $paperSize, $orientation, $forPdf);
    }

    /* =====================================================================
     |  RENDER PATHS
     |===================================================================== */

    /**
     * Render untuk snapshot HTML lama: patch orientasi/ukuran kertas,
     * lalu injeksikan watermark ke dalam markup yang sudah ada.
     */
    private function renderFromLegacySnapshot(
        Perizinan $perizinan,
        string $snapshot,
        string $paperSize,
        string $orientation
    ): string {
        $html = $this->patchSnapshotOrientation($snapshot, $paperSize, $orientation);

        $watermarkHtml = $this->buildWatermarkHtml($perizinan);

        return preg_replace('/(<div class="print-content">)/i', $watermarkHtml . '$1', $html);
    }

    /**
     * Render dari body mentah (raw body) dengan membangun ulang dokumen HTML lengkap
     * beserta CSS halaman, padding, watermark, dsb.
     */
    private function renderFromBody(
        Perizinan $perizinan,
        ?string $snapshot,
        string $paperSize,
        string $orientation,
        bool $forPdf
    ): string {
        $body = !empty($snapshot) ? $snapshot : $perizinan->replaceVariables(false);
        $body = preg_replace('/(<br\s*\/?>(\s)*)+$/i', '', $body);

        $isLandscape = $orientation === 'landscape';

        $preset = CetakPreset::where('dinas_id', $perizinan->dinas_id)
            ->where('is_active', true)
            ->first();

        // Tinggi spesifik kertas (dikurangi 1-2mm agar tidak memicu blank page ke-2 di DOMPDF)
        $contentHeight = $this->resolveContentHeight($paperSize, $isLandscape);

        // Padding: pakai preset jika ada, kalau tidak, ikuti nilai default dari template-editor.css
        $padding = ($preset && ($preset->margin_top || $preset->margin_bottom || $preset->margin_left || $preset->margin_right))
            ? $this->getContentPadding($preset, $orientation)
            : ($isLandscape ? '1.5cm 2cm' : '1.5cm 1.5cm');

        $pageCss       = $this->buildPageCss($paperSize, $orientation, $forPdf);
        $fontSize      = $isLandscape ? '9.5pt' : '10.5pt';
        $watermarkHtml = $this->buildWatermarkHtml($perizinan);

        return $this->buildDocument($pageCss, $fontSize, $contentHeight, $padding, $watermarkHtml, $body);
    }

    /* =====================================================================
     |  HTML BUILDERS
     |===================================================================== */

    /**
     * Susun dokumen HTML lengkap (head + body) untuk hasil render baru.
     */
    private function buildDocument(
        string $pageCss,
        string $fontSize,
        string $contentHeight,
        string $padding,
        string $watermarkHtml,
        string $body
    ): string {
        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        ' . $pageCss . '
        * { box-sizing: border-box; }
        html, body {
            margin: 0; padding: 0; width: 100%; height: 100%;
            font-family: "Times New Roman", Times, serif;
            font-size: ' . $fontSize . ' !important;
            line-height: 1.15; color: #000; background: #fff;
        }
        /* PENTING: Jangan gunakan width: 100% pada .print-page bersamaan dengan padding.
           DOMPDF memiliki bug box-sizing: border-box sehingga elemen akan meluber keluar kertas.
           Gunakan width: auto (default dari block element) */
        .print-page {
            position: relative;
            min-height: ' . $contentHeight . ';
            max-height: ' . $contentHeight . ';
            padding: ' . $padding . ';
            overflow: hidden;
            margin: 0 auto;
        }
        .print-content {
            position: relative; z-index: 2; height: 100%;
        }
        figure { margin: 0; padding: 0; }
        figure.image { display: block !important; width: 100% !important; text-align: center !important; margin-bottom: 5px !important; clear: both !important; }
        figure.image img { display: inline-block !important; margin: 0 auto !important; max-width: 100%; height: auto; }
        p { clear: both; margin-top: 0; margin-bottom: 4px; text-align: justify; line-height: 1.15 !important; }
        p:last-child { margin-bottom: 0 !important; }
        h1, h2, h3, h4 { margin-top: 5px !important; margin-bottom: 5px !important; }
        table { border-collapse: collapse; width: 100% !important; max-width: 100% !important; margin-bottom: 5px; }
        tr { page-break-inside: auto; page-break-after: auto; }
        td { vertical-align: top; padding: 1px 3px; border: none; word-wrap: break-word; }
        .signature-block { margin-top: 5px; }
        .print-qr-floating {
            position: absolute;
            left: 5mm;
            bottom: 5mm;
            z-index: 30;
            width: 24mm;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 6.8pt;
            line-height: 1.05;
            color: #333;
        }
        .print-qr-floating img {
            width: 16mm !important;
            height: 16mm !important;
            display: block;
            margin: 0 auto 1.5mm auto;
        }
        .print-qr-floating span { display: block; margin-top: 1mm; }
    </style>
</head>
<body>
    <div class="print-page">
        ' . $watermarkHtml . '
        <div class="print-content">
            ' . $body . '
        </div>
    </div>
</body>
</html>';
    }

    /**
     * Patch CSS `@page` dan `.print-page` di dalam snapshot HTML lama
     * agar mengikuti ukuran kertas & orientasi yang diminta.
     */
    private function patchSnapshotOrientation(string $html, string $paperSize, string $orientation): string
    {
        if (strtoupper($paperSize) === 'F4') {
            $newSize = $orientation === 'landscape' ? '330mm 215mm' : '215mm 330mm';
        } else {
            $newSize = strtolower($paperSize) . ' ' . $orientation;
        }

        $isLandscape = $orientation === 'landscape';
        $padding     = $isLandscape ? '10mm 25mm 5mm 25mm' : '25mm 30mm 15mm 30mm';

        // Ganti nilai `size` di dalam blok @page, pertahankan properti lain apa adanya
        $patched = preg_replace_callback('/@page\s*[^{]*\{([^}]*)\}/i', function ($m) use ($newSize) {
            $inner = preg_replace('/size\s*:[^;]+;?/i', '', $m[1]);
            return '@page { size: ' . $newSize . '; ' . trim($inner) . ' }';
        }, $html);

        $contentHeight = $this->resolveContentHeight($paperSize, $isLandscape);

        // Hapus hardcoded max-height/min-height dari .print-page yang membuat konten jadi 2 halaman,
        // lalu ganti dengan dimensi kertas spesifik agar tetap 1 lembar dan tidak tumpah.
        // PENTING: Jangan gunakan width: 100% atau width spesifik bersamaan dengan padding — DOMPDF
        // punya bug box-sizing: border-box yang membuatnya melebar ke luar kertas. Gunakan width: auto.
        $patched = preg_replace(
            '/\.print-page\s*\{[^}]*\}/i',
            '.print-page { position: relative; min-height: ' . $contentHeight . '; max-height: ' . $contentHeight . '; padding: ' . $padding . '; overflow: hidden; margin: 0 auto; page-break-after: avoid; page-break-inside: avoid; }',
            $patched
        );

        return $patched ?? $html;
    }

    /* =====================================================================
     |  CSS / LAYOUT HELPERS
     |===================================================================== */

    private function buildPageCss(string $paperSize, string $orientation, bool $forPdf): string
    {
        if ($forPdf) {
            return '@page { margin: 0; }';
        }

        $size = strtoupper($paperSize) === 'F4'
            ? ($orientation === 'landscape' ? '330mm 215mm' : '215mm 330mm')
            : (strtolower($paperSize) . ' ' . $orientation);

        return "@page { size: {$size}; margin: 0; }";
    }

    /**
     * Tinggi konten spesifik per ukuran kertas & orientasi.
     * Nilai sengaja dikurangi 1-2mm dari ukuran kertas asli agar tidak memicu blank page ke-2 di DOMPDF.
     */
    private function resolveContentHeight(string $paperSize, bool $isLandscape): string
    {
        if (strtoupper($paperSize) === 'F4') {
            return $isLandscape ? '213mm' : '328mm';
        }

        // A4
        return $isLandscape ? '208mm' : '295mm';
    }

    private function getContentPadding($preset, string $orientation): string
    {
        $isLandscape = $orientation === 'landscape';

        if ($preset) {
            $mt = $preset->margin_top    ?? ($isLandscape ? 1.0 : 2.5);
            $mr = $preset->margin_right  ?? ($isLandscape ? 2.5 : 3.0);
            $mb = $preset->margin_bottom ?? ($isLandscape ? 0.5 : 1.5);
            $ml = $preset->margin_left   ?? ($isLandscape ? 2.5 : 3.0);

            return "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
        }

        return $isLandscape ? '1.0cm 2.5cm 0.5cm 2.5cm' : '2.5cm 3cm 1.5cm 3cm';
    }

    /* =====================================================================
     |  WATERMARK
     |===================================================================== */

    private function buildWatermarkHtml(Perizinan $p): string
    {
        $dinas = $p->dinas;

        if (!$dinas) {
            return '';
        }

        $html = '';
        $html .= $this->buildBorderWatermark($dinas, $p->jenisPerizinan);
        $html .= $this->buildCenterWatermark($dinas);

        return $html;
    }

    private function buildBorderWatermark($dinas, $jenisPerizinan): string
    {
        if (!(($dinas->watermark_enabled ?? true) && ($jenisPerizinan->use_border ?? false))) {
            return '';
        }

        $borderSrc = $this->imageToBase64($dinas->watermark_border_img);

        if (!$borderSrc) {
            return '';
        }

        $opacity = number_format(max(0, min(1, $dinas->watermark_border_opacity ?? 0.9)), 2);

        return '
                <div style="position:absolute;top:0;left:0;right:0;bottom:0;opacity:' . $opacity . ';z-index:0;pointer-events:none;">
                    <img src="' . $borderSrc . '" style="width:100%;height:100%;" alt="">
                </div>';
    }

    private function buildCenterWatermark($dinas): string
    {
        if (!($dinas->watermark_enabled ?? true)) {
            return '';
        }

        $wmSrc = $this->imageToBase64($dinas->watermark_img ?: $dinas->logo);

        if (!$wmSrc) {
            return '';
        }

        $opacity = number_format(max(0, min(1, $dinas->watermark_opacity ?? 0.07)), 2);

        return '
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:180mm;height:180mm;opacity:' . $opacity . ';z-index:1;pointer-events:none;">
                    <img src="' . $wmSrc . '" style="width:100%;height:100%;object-fit:contain;" alt="">
                </div>';
    }

    private function imageToBase64(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        $ext  = pathinfo($path, PATHINFO_EXTENSION);
        $data = Storage::disk('public')->get($path);

        return 'data:image/' . $ext . ';base64,' . base64_encode($data);
    }
}