<?php

namespace App\Actions\Perizinan;

use App\Models\CetakPreset;
use App\Models\Perizinan;
use Illuminate\Support\Facades\Storage;

class RenderHtmlAction
{
    /**
     * Ukuran kertas yang didukung (dalam milimeter).
     * Nilai ini SEKARANG benar-benar dipakai untuk menghitung tinggi konten
     * secara presisi (paperHeight - padding), bukan sekadar dokumentasi.
     */
    private const PAPER_SIZES = [
        'A4' => ['w_mm' => 210.0, 'h_mm' => 297.0],
        'F4' => ['w_mm' => 215.0, 'h_mm' => 330.0],
        'A3' => ['w_mm' => 297.0, 'h_mm' => 420.0],
        'LETTER' => ['w_mm' => 215.9, 'h_mm' => 279.4],
    ];

    /**
     * Buffer kecil (mm) untuk menghindari blank/overflow page ke-2 akibat
     * pembulatan sub-pixel. Nilainya BERBEDA antara DOMPDF dan browser
     * (Chrome) karena kedua engine merender font Times New Roman dengan
     * metric yang sedikit berbeda — buffer presisi untuk DOMPDF ternyata
     * terlalu kecil untuk Chrome, itulah sumber print-html "tidak rapi"
     * walau PDF sudah pas.
     */
    // 3mm buffer — lebih aman dari 0.4 tanpa terlalu agresif.
    // Lihat juga: .print-page sekarang overflow:visible & max-height:none
    // agar DOMPDF tidak paksa page-break di tengah signature block.
    private const SAFETY_BUFFER_MM_PDF  = 3.0;
    private const SAFETY_BUFFER_MM_HTML = 1.5;

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

        $paperSize = strtoupper($paperSize ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4')));
        $orientation = strtolower($orientation ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait')));

        if (!isset(self::PAPER_SIZES[$paperSize])) {
            $paperSize = 'A4';
        }

        $snapshot = $perizinan->snapshot_html;

        // Kasus 1: snapshot sudah berupa HTML utuh (dari sistem lama) → patch ukurannya saja
        if (!empty($snapshot) && stripos($snapshot, '<html') !== false) {
            return $this->renderFromLegacySnapshot($perizinan, $snapshot, $paperSize, $orientation, $preset, $forPdf);
        }

        // Kasus 2: snapshot berupa raw body (sistem baru), atau belum ada snapshot sama sekali
        return $this->renderFromBody($perizinan, $snapshot, $paperSize, $orientation, $forPdf, $preset);
    }

    /* =====================================================================
     |  RENDER PATHS
     |===================================================================== */

    private function renderFromLegacySnapshot(
        Perizinan $perizinan,
        string $snapshot,
        string $paperSize,
        string $orientation,
        ?CetakPreset $preset,
        bool $forPdf
    ): string {
        $html = $this->patchSnapshotOrientation($snapshot, $paperSize, $orientation, $preset, $forPdf);

        $watermarkHtml = $this->buildWatermarkHtml($perizinan);

        return preg_replace('/(<div class="print-content">)/i', $watermarkHtml . '$1', $html);
    }

    private function renderFromBody(
        Perizinan $perizinan,
        ?string $snapshot,
        string $paperSize,
        string $orientation,
        bool $forPdf,
        ?CetakPreset $preset
    ): string {
        $body = !empty($snapshot) ? $snapshot : $perizinan->replaceVariables(false);
        
        // BANYAK TERJADI: User menekan Enter beberapa kali di akhir editor TinyMCE,
        // menghasilkan <p><br></p> atau <br> yang mendorong tanda tangan ke halaman 2.
        // Kita WAJIB hapus semua elemen kosong di akhir konten!
        $body = preg_replace('/(<br\s*\/?>|\s)+$/i', '', $body);
        $body = preg_replace('/(<p>(&nbsp;|\s|<br\s*\/?>)*<\/p>\s*)+$/i', '', $body);

        $isLandscape = $orientation === 'landscape';

        // Padding: pakai preset jika ada, kalau tidak, default proporsional per orientasi
        $padding = ($preset && ($preset->margin_top || $preset->margin_bottom || $preset->margin_left || $preset->margin_right))
            ? $this->getContentPadding($preset, $orientation)
            : ($isLandscape ? '1.5cm 2cm' : '1.5cm 1.5cm');

        // Tinggi konten dihitung PRESISI dari ukuran kertas asli dikurangi padding aktual,
        // bukan angka hardcode yang mengasumsikan padding tertentu. Buffer disesuaikan
        // dengan engine render (DOMPDF untuk PDF, Chrome untuk preview/print HTML).
        $contentHeight = $this->resolveContentHeight($paperSize, $isLandscape, $padding, $forPdf);

        $pageCss       = $this->buildPageCss($paperSize, $orientation, $forPdf);
        
        // PENGECILAN FONT & LINE-HEIGHT KHUSUS PDF:
        // Di Linux, font fallback DOMPDF lebih tinggi/lebar dari Windows.
        if ($forPdf) {
            $fontSize   = $isLandscape ? '8.5pt' : '9.5pt';
            $lineHeight = '1.05';
        } else {
            $fontSize   = $isLandscape ? '9.5pt' : '10.5pt';
            $lineHeight = '1.15';
        }
        
        $watermarkHtml = $this->buildWatermarkHtml($perizinan);

        return $this->buildDocument($pageCss, $fontSize, $lineHeight, $contentHeight, $padding, $watermarkHtml, $body);
    }

    /* =====================================================================
     |  HTML BUILDERS
     |===================================================================== */

    private function buildDocument(
        string $pageCss,
        string $fontSize,
        string $lineHeight,
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
            line-height: ' . $lineHeight . '; color: #000; background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* PENTING — jangan ubah pola ini:
           Jangan gunakan width: 100% pada .print-page bersamaan dengan padding.
           DOMPDF punya bug box-sizing: border-box yang membuat elemen meluber
           keluar batas kertas ketika width eksplisit + padding dipakai bersamaan.
           Karena .print-page adalah elemen block di dalam elemen BODY yang sudah
           width:100%, ia otomatis mengambil lebar penuh halaman tanpa perlu
           width eksplisit — sehingga bug tersebut tidak terpicu.

           KENAPA max-height:none & overflow:visible?
           max-height + overflow:hidden memaksa DOMPDF memotong layout tepat di batas
           tinggi kotak, lalu memindahkan overflow ke halaman baru. Hasilnya:
           signature/QR yang hanya selisih beberapa mm loncat ke halaman 2.
           Dengan overflow:visible, DOMPDF mengikuti page-break alami (batas fisik kertas),
           bukan batas kotak — sehingga selama total konten muat 1 halaman, tetap 1 halaman. */
        .print-page {
            position: relative;
            min-height: ' . $contentHeight . ';
            max-height: none;
            padding: ' . $padding . ';
            overflow: visible;
            margin: 0 auto;
            page-break-after: avoid;
            page-break-inside: avoid;
        }
        .print-content {
            position: relative; z-index: 2; height: 100%;
        }

        /* Tipografi & spacing dirapikan supaya konsisten di semua template */
        figure { margin: 0; padding: 0; }
        figure.image {
            display: block !important; width: 100% !important;
            text-align: center !important; margin-bottom: 4px !important; clear: both !important;
        }
        figure.image img { display: inline-block !important; margin: 0 auto !important; max-width: 100%; height: auto; }

        p {
            clear: both; margin-top: 0; margin-bottom: 3px;
            text-align: justify; line-height: ' . $lineHeight . ' !important;
            orphans: 3; widows: 3;
        }
        p:last-child { margin-bottom: 0 !important; }

        h1, h2, h3, h4 {
            margin-top: 4px !important; margin-bottom: 4px !important;
            letter-spacing: 0.2px;
        }

        table {
            border-collapse: collapse; width: 100% !important; max-width: 100% !important;
            margin-bottom: 4px; page-break-inside: avoid;
        }
        tr { page-break-inside: avoid !important; page-break-after: auto; }
        td { vertical-align: top; padding: 1px 3px; border: none; word-wrap: break-word; }

        .signature-block {
            margin-top: 5px;
            /* Pastikan seluruh blok tanda tangan (nama, jabatan, NIP, dst)
               TIDAK pernah terpotong ke halaman berikutnya. */
            page-break-inside: avoid !important;
            break-inside:      avoid !important;
            page-break-before: avoid !important;
            break-before:      avoid !important;
        }
        .signature-block * {
            page-break-inside: avoid !important;
            break-inside:      avoid !important;
        }

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

    private function patchSnapshotOrientation(string $html, string $paperSize, string $orientation, ?CetakPreset $preset, bool $forPdf): string
    {
        if ($paperSize === 'F4') {
            $newSize = $orientation === 'landscape' ? '330mm 215mm' : '215mm 330mm';
        } else {
            $newSize = strtolower($paperSize) . ' ' . $orientation;
        }

        $isLandscape = $orientation === 'landscape';
        $padding = ($preset && ($preset->margin_top || $preset->margin_bottom || $preset->margin_left || $preset->margin_right))
            ? $this->getContentPadding($preset, $orientation)
            : ($isLandscape ? '1.0cm 2.5cm 0.5cm 2.5cm' : '2.5cm 3cm 1.5cm 3cm');

        $patched = preg_replace_callback('/@page\s*[^{]*\{([^}]*)\}/i', function ($m) use ($newSize) {
            $inner = preg_replace('/size\s*:[^;]+;?/i', '', $m[1]);
            return '@page { size: ' . $newSize . '; ' . trim($inner) . ' }';
        }, $html);

        $contentHeight = $this->resolveContentHeight($paperSize, $isLandscape, $padding, $forPdf);

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

        $size = $paperSize === 'F4'
            ? ($orientation === 'landscape' ? '330mm 215mm' : '215mm 330mm')
            : (strtolower($paperSize) . ' ' . $orientation);

        return "@page { size: {$size}; margin: 0; }";
    }

    /**
     * Tinggi konten dihitung PRESISI: tinggi kertas asli dikurangi padding
     * atas & bawah yang SEBENARNYA dipakai, dikurangi buffer kecil untuk
     * menghindari blank page ke-2 akibat pembulatan sub-pixel di DOMPDF.
     *
     * Ini menggantikan pendekatan lama yang mengurangi 1-2mm secara asal
     * tanpa memperhitungkan padding aktual — itulah sumber ketidakpresisian
     * saat preset margin custom dipakai.
     */
    private function resolveContentHeight(string $paperSize, bool $isLandscape, string $padding, bool $forPdf = true): string
    {
        $size = self::PAPER_SIZES[$paperSize] ?? self::PAPER_SIZES['A4'];
        $paperHeightMm = $isLandscape ? $size['w_mm'] : $size['h_mm'];

        [$topMm, , $bottomMm,] = $this->parsePaddingToMm($padding);

        $buffer = $forPdf ? self::SAFETY_BUFFER_MM_PDF : self::SAFETY_BUFFER_MM_HTML;
        $height = $paperHeightMm - $topMm - $bottomMm - $buffer;

        // Jaga-jaga: jangan sampai negatif/terlalu kecil kalau padding sangat besar
        $height = max($height, 20.0);

        return number_format($height, 2, '.', '') . 'mm';
    }

    /**
     * Parser shorthand CSS padding ("2.5cm 3cm 1.5cm 3cm", "1cm 2cm", dst)
     * menjadi array [top, right, bottom, left] dalam milimeter.
     */
    private function parsePaddingToMm(string $padding): array
    {
        $parts = preg_split('/\s+/', trim($padding));
        $parts = array_filter($parts, fn($p) => $p !== '');
        $parts = array_values($parts);

        switch (count($parts)) {
            case 1:
                $parts = array_fill(0, 4, $parts[0]);
                break;
            case 2:
                $parts = [$parts[0], $parts[1], $parts[0], $parts[1]];
                break;
            case 3:
                $parts = [$parts[0], $parts[1], $parts[2], $parts[1]];
                break;
            case 4:
                break;
            default:
                $parts = ['15mm', '15mm', '15mm', '15mm'];
        }

        return array_map(fn($v) => $this->cssLengthToMm($v), $parts);
    }

    private function cssLengthToMm(string $value): float
    {
        if (preg_match('/^([\d.]+)\s*(mm|cm|in|px)?$/i', trim($value), $m)) {
            $num = (float) $m[1];
            $unit = strtolower($m[2] ?? 'mm');

            return match ($unit) {
                'cm' => $num * 10,
                'in' => $num * 25.4,
                'px' => $num * 25.4 / 96,
                default => $num, // mm
            };
        }

        return 15.0; // fallback aman
    }

    private function getContentPadding($preset, string $orientation): string
    {
        $isLandscape = $orientation === 'landscape';

        if ($preset) {
            $mt = $preset->margin_top ?? ($isLandscape ? 1.0 : 2.5);
            $mr = $preset->margin_right ?? ($isLandscape ? 2.5 : 3.0);
            $mb = $preset->margin_bottom ?? ($isLandscape ? 0.5 : 1.5);
            $ml = $preset->margin_left ?? ($isLandscape ? 2.5 : 3.0);

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
                <div style="position:absolute;top:0;left:0;right:0;bottom:0;opacity:' . $opacity . ';z-index:0;pointer-events:none;-webkit-print-color-adjust:exact;print-color-adjust:exact;">
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
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:180mm;height:180mm;opacity:' . $opacity . ';z-index:1;pointer-events:none;-webkit-print-color-adjust:exact;print-color-adjust:exact;">
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

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $data = Storage::disk('public')->get($path);

        return 'data:image/' . $ext . ';base64,' . base64_encode($data);
    }
}