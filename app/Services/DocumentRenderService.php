<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentRenderService
{
    public function renderHtml(Perizinan $p, $preset = null, $paperSize = null, $orientation = null): string
    {
        // [REFAC 1] Injeksi Limit Memori dan Waktu Eksekusi
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');

        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        $body = $p->snapshot_html;
        if (empty($body)) {
            $body = $p->replaceVariables(false);
        }

        // Pembersih Spasi Gaib
        $body = preg_replace('/(<p>(&nbsp;|\s|<br\s*\/?>)*<\/p>\s*)+$/i', '', $body);
        $body = preg_replace('/(<br\s*\/?>(\s)*)+$/i', '', $body);

        $pageCss = $this->buildPageCss($preset, $paperSize, $orientation);
        $padding = $this->getContentPadding($preset, $orientation);

        $isLandscape = strtolower($orientation ?: ($preset->orientation ?? 'portrait')) === 'landscape';
        $fontSize = $isLandscape ? '9.5pt' : '10.5pt';

        // Watermark: logo dinas di tengah halaman dengan opacity rendah
        $dinas = $p->dinas;
        $watermarkHtml = '';
        $watermarkCss = '';
        $watermarkEnabled = $dinas->watermark_enabled ?? true;
        $wmSize = (int) ($dinas->watermark_size ?? 200);

        // [REFAC 2] Cloud-ready Storage Fetching (Menggunakan get() dan exists())
        $wmContent = null;
        $wmExtension = 'png';
        $wmOpacity = $dinas->watermark_opacity ?? 0.08;

        if ($watermarkEnabled && $dinas) {
            if (!empty($dinas->watermark_img) && Storage::disk('public')->exists($dinas->watermark_img)) {
                $wmContent = Storage::disk('public')->get($dinas->watermark_img);
                $wmExtension = strtolower(pathinfo($dinas->watermark_img, PATHINFO_EXTENSION)) ?: 'png';
            } elseif (!empty($dinas->logo) && Storage::disk('public')->exists($dinas->logo)) {
                $wmContent = Storage::disk('public')->get($dinas->logo);
                $wmExtension = strtolower(pathinfo($dinas->logo, PATHINFO_EXTENSION)) ?: 'png';
            }
        }

        if ($wmContent) {
            $wmBase64 = base64_encode($wmContent);
            $wmSrc = "data:image/{$wmExtension};base64,{$wmBase64}";

            $watermarkCss = '
                .watermark-overlay {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: ' . $wmSize . 'px;
                    height: ' . $wmSize . 'px;
                    opacity: ' . $wmOpacity . ';
                    z-index: -1;
                }
            ';
            $watermarkHtml = '<div class="watermark-overlay"><img src="' . $wmSrc . '" style="width:' . $wmSize . 'px; height:' . $wmSize . 'px; object-fit:contain;"></div>';
        }

        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                ' . $pageCss . '
                ' . $watermarkCss . '

                body {
                    font-family: "Times New Roman", Times, serif;
                    font-size: ' . $fontSize . ' !important;
                    line-height: 1.1;
                    color: #000;
                    margin: 0;
                    padding: ' . $padding . ';
                    background-image-resize: 6;
                }

                figure { margin: 0; padding: 0; }
                figure.image { display: block !important; width: 100% !important; text-align: center !important; margin-bottom: 5px !important; clear: both !important; }
                figure.image img { display: inline-block !important; margin: 0 auto !important; max-width: 100%; height: auto; }

                p { clear: both; margin-top: 0; margin-bottom: 4px; }
                p:last-child { margin-bottom: 0 !important; }

                .container {
                    width: 100%;
                    margin: 0 auto;
                    box-sizing: border-box; 
                }

                table { border-collapse: collapse; width: 100% !important; max-width: 100% !important; page-break-inside: avoid; margin-bottom: 5px; }
                tr { page-break-inside: avoid; page-break-after: auto; }
                td { vertical-align: top; padding: 1px 3px; border: none; }

                .signature-block { page-break-inside: avoid; margin-top: 5px; }
            </style>
        </head>
        <body>
            ' . $watermarkHtml . '
            ' . $body . '
        </body>
        </html>';
    }

    public function generatePdf(Perizinan $p, $paperSize = null, $orientation = null)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $p->dinas_id)
            ->where('is_active', true)
            ->first();

        $paperSize = $paperSize ?: ($preset->paper_size ?? 'A4');
        $orientation = $orientation ?: ($preset->orientation ?? 'portrait');

        $html = $this->renderHtml($p, $preset, $paperSize, $orientation);

        if (strtoupper($paperSize) === 'F4') {
            if (strtolower($orientation) === 'landscape') {
                $paperSizeArray = [0, 0, 935.43, 595.28];
                $orientation = 'portrait'; 
            } else {
                $paperSizeArray = [0, 0, 595.28, 935.43];
            }
        } else {
            $paperSizeArray = strtolower($paperSize);
        }

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizeArray, $orientation)
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'Times New Roman',
                'dpi' => 96,
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
        $name = str_replace(' ', '-', $name);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);

        return $name;
    }

    public function storePermanentPdf(Perizinan $perizinan): string
    {
        // [REFAC 3] Dinamisasi Parameter Kertas untuk Penyimpanan Permanen
        $preset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
            ->where('is_active', true)
            ->first();

        $paperSize = $preset->paper_size ?? 'A4';
        $orientation = $preset->orientation ?? 'portrait';

        $html = $this->renderHtml($perizinan, $preset, $paperSize, $orientation);

        // Kalkulasi ulang kertas (khusus penanganan F4)
        if (strtoupper($paperSize) === 'F4') {
            if (strtolower($orientation) === 'landscape') {
                $paperSizeArray = [0, 0, 935.43, 595.28];
                $orientation = 'portrait';
            } else {
                $paperSizeArray = [0, 0, 595.28, 935.43];
            }
        } else {
            $paperSizeArray = strtolower($paperSize);
        }

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizeArray, $orientation)
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'Times New Roman',
                'dpi' => 96,
            ]);

        $folder = 'issued_pdfs/' . date('Y/m');
        if (!Storage::disk('local')->exists($folder)) {
            Storage::disk('local')->makeDirectory($folder);
        }

        $filename = "{$perizinan->id}_" . time() . ".pdf";
        $path = "{$folder}/{$filename}";

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    private function buildPageCss($preset, $paperSizeOverride = null, $orientationOverride = null): string
    {
        $paperSize = strtoupper($paperSizeOverride ?: ($preset->paper_size ?? 'A4'));
        $orientation = strtolower($orientationOverride ?: ($preset->orientation ?? 'portrait'));

        if ($paperSize === 'F4') {
            if ($orientation === 'landscape') {
                $size = "330mm 210mm";
            } else {
                $size = "210mm 330mm";
            }
        } else {
            $size = "{$paperSize} {$orientation}";
        }

        return "@page { size: {$size}; margin: 0px; }";
    }

    private function getContentPadding($preset, $orientationOverride): string
    {
        $orientation = strtolower($orientationOverride ?: ($preset->orientation ?? 'portrait'));

        if ($preset) {
            $mt = $preset->margin_top ?? ($orientation === 'landscape' ? 1.0 : 2.5);
            $mr = $preset->margin_right ?? ($orientation === 'landscape' ? 2.5 : 3.0);
            $mb = $preset->margin_bottom ?? ($orientation === 'landscape' ? 0.5 : 1.5);
            $ml = $preset->margin_left ?? ($orientation === 'landscape' ? 2.5 : 3.0);

            // [REFAC 4] Penghapusan "Force Override" kaku. 
            // Kita mempercayakan sepenuhnya pada nilai dari database ($preset).
            return "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
        }

        if ($orientation === 'landscape')
            return "1.0cm 2.5cm 0.5cm 2.5cm";
        return "2.5cm 3cm 1.5cm 3cm";
    }
}