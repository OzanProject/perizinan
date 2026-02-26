<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentRenderService
{
    public function renderHtml(Perizinan $p, $preset = null, $paperSize = null, $orientation = null): string
    {
        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        $body = $p->replaceVariables();

        // =========================================================================
        // TRIK ANTI-LONCAT 1: PEMBERSIH SPASI GAIB
        // Menghapus tag <p> kosong atau <br> berlebih di paling bawah dokumen 
        // yang sering ditambahkan otomatis oleh CKEditor dan memicu halaman ke-2.
        // =========================================================================
        $body = preg_replace('/(<p>(&nbsp;|\s|<br\s*\/?>)*<\/p>\s*)+$/i', '', $body);
        $body = preg_replace('/(<br\s*\/?>\s*)+$/i', '', $body);

        $pageCss = $this->buildPageCss($preset, $paperSize, $orientation);

        // Ambil padding cerdas yang menyesuaikan orientasi kertas
        $padding = $this->getContentPadding($preset, $orientation);

        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                /* Kertas 0 Margin agar Bingkai Full */
                ' . $pageCss . '
                
                body { 
                    font-family: "Times New Roman", Times, serif; 
                    /* TRIK ANTI-LONCAT 2: Font dikecilkan jadi 10.5pt agar tabel muat */
                    font-size: 10.5pt; 
                    line-height: 1.15; 
                    color: #000; 
                    margin: 0; 
                    padding: ' . $padding . '; 
                }

                /* Fix Logo CKEditor */
                figure { margin: 0; padding: 0; }
                figure.image {
                    display: block !important;
                    width: 100% !important;
                    text-align: center !important;
                    margin-bottom: 5px !important;
                    clear: both !important;
                }
                figure.image img {
                    display: inline-block !important;
                    margin: 0 auto !important;
                    max-width: 100%;
                    height: auto;
                }
                .image-style-align-left { text-align: left !important; }
                .image-style-align-left img { float: left !important; margin-right: 15px !important; }
                .image-style-align-center { text-align: center !important; }
                .image-style-align-center img { margin-left: auto !important; margin-right: auto !important; }
                .image-style-align-right { text-align: right !important; }
                .image-style-align-right img { float: right !important; margin-left: 15px !important; }

                /* TRIK ANTI-LONCAT 3: Memampatkan jarak margin paragraf */
                p { clear: both; margin-top: 0; margin-bottom: 4px; }
                p:last-child { margin-bottom: 0 !important; }
                div:last-child { margin-bottom: 0 !important; }

                /* Tabel & Tanda Tangan */
                table { border-collapse: collapse; width: 100%; page-break-inside: avoid; }
                tr { page-break-inside: avoid; page-break-after: auto; }
                td { vertical-align: top; padding: 2px 4px; border: none; }
                
                /* Tanda tangan tidak boleh terbelah */
                .signature-block { page-break-inside: avoid; margin-top: 5px; }
            </style>
        </head>
        <body>
            ' . $body . '
        </body>
        </html>';
    }

    public function generatePdf(Perizinan $p, $paperSize = null, $orientation = null)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $p->dinas_id)
            ->where('is_active', true)
            ->first();

        // Pastikan orientation terdefinisi agar Smart Padding berfungsi
        $paperSize = $paperSize ?: ($preset->paper_size ?? 'A4');
        $orientation = $orientation ?: ($preset->orientation ?? 'portrait');

        $html = $this->renderHtml($p, $preset, $paperSize, $orientation);

        if (strtoupper($paperSize) === 'F4') {
            $paperSizeArray = [0, 0, 595.28, 935.43]; // 210mm x 330mm
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
        $html = $this->renderHtml($perizinan);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
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
            $size = "210mm 330mm {$orientation}";
        } else {
            $size = "{$paperSize} {$orientation}";
        }

        return "
        @page {
            size: {$size};
            margin: 0px; 
        }
        ";
    }

    private function getContentPadding($preset, $orientationOverride): string
    {
        $orientation = strtolower($orientationOverride ?: ($preset->orientation ?? 'portrait'));

        // TRIK ANTI-LONCAT 4: SMART PADDING
        // Menipiskan batas bawah (margin-bottom) agar teks tidak terdorong ke halaman baru
        if ($preset) {
            $mt = $preset->margin_top ?? ($orientation === 'landscape' ? 1.5 : 2.5);
            $mr = $preset->margin_right ?? ($orientation === 'landscape' ? 2.5 : 3.0);
            $mb = $preset->margin_bottom ?? ($orientation === 'landscape' ? 1.0 : 1.5); // Sengaja dibuat tipis (1cm) di bawah!
            $ml = $preset->margin_left ?? ($orientation === 'landscape' ? 2.5 : 3.0);

            return "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
        }

        // Default super aman jika preset tidak di-setting
        if ($orientation === 'landscape') {
            return "1.5cm 2.5cm 1.0cm 2.5cm";
        }

        return "2.5cm 3cm 1.5cm 3cm";
    }
}