<?php

namespace App\Actions\Perizinan;

use App\Models\CetakPreset;
use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePdfAction
{
    public function __construct(protected RenderHtmlAction $renderHtmlAction) {}

    public function handle(Perizinan $perizinan, ?string $paperSize = null, ?string $orientation = null)
    {
        $perizinan->load(['lembaga', 'jenisPerizinan', 'dinas']);

        $preset = CetakPreset::where('dinas_id', $perizinan->dinas_id)
            ->where('is_active', true)->first();

        $paperSize   = strtoupper($paperSize ?: ($perizinan->jenisPerizinan->paper_size ?: ($preset->paper_size ?? 'A4')));
        $orientation = strtolower($orientation ?: ($perizinan->jenisPerizinan->orientation ?: ($preset->orientation ?? 'portrait')));

        // === Opsi A: DOCX Template → HTML → PDF ===
        // Jika Jenis Perizinan sudah diupload template DOCX-nya, gunakan sebagai sumber PDF.
        // Keuntungan: layout PDF mengikuti desain Word yang sudah diatur di template DOCX.
        if ($perizinan->jenisPerizinan && $perizinan->jenisPerizinan->template_word_path) {
            $templatePath = storage_path('app/public/' . $perizinan->jenisPerizinan->template_word_path);

            if (file_exists($templatePath)) {
                $result = $this->renderFromDocx($perizinan, $templatePath, $paperSize, $orientation);
                if ($result !== null) {
                    return $result;
                }
                // Jika gagal, fallthrough ke metode HTML biasa
            }
        }

        // === Fallback: HTML Template (TinyMCE) → DOMPDF ===
        $html = $this->renderHtmlAction->handle($perizinan, $paperSize, $orientation, true);

        return $this->streamPdf($html, $paperSize, $orientation, $perizinan);
    }

    /**
     * Render PDF dari DOCX template:
     * 1. Isi variabel via PhpWord TemplateProcessor
     * 2. Convert DOCX → HTML via PhpWord HTML Writer
     * 3. Inject CSS cetak + watermark/QR ke HTML
     * 4. Render PDF via DOMPDF
     */
    private function renderFromDocx(
        Perizinan $perizinan,
        string $templatePath,
        string $paperSize,
        string $orientation
    ): mixed {
        try {
            // Step 1: Isi variabel ke DOCX template
            $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            $template->setValue('NOMOR_SURAT',     $perizinan->nomor_surat ?? '-');
            $template->setValue('NAMA_LEMBAGA',    $perizinan->lembaga->nama_lembaga ?? '-');
            $template->setValue('NPSN',            $perizinan->lembaga->npsn ?? '-');
            $template->setValue('ALAMAT_LEMBAGA',  $perizinan->lembaga->alamat ?? '-');
            $template->setValue('TANGGAL_TERBIT',  $perizinan->tanggal_terbit
                ? $perizinan->tanggal_terbit->translatedFormat('d F Y') : '-');
            $template->setValue('MASA_BERLAKU',    $perizinan->masa_berlaku
                ? $perizinan->masa_berlaku->translatedFormat('d F Y') : '-');

            // Variabel Dinas
            $dinas = $perizinan->dinas;
            if ($dinas) {
                $template->setValue('KOTA_DINAS',       $dinas->kota ?? '');
                $template->setValue('ALAMAT_DINAS',     $dinas->alamat ?? '');
                $template->setValue('PIMPINAN_NAMA',    $dinas->nama_pimpinan ?? '');
                $template->setValue('PIMPINAN_NIP',     $dinas->nip_pimpinan ?? '');
                $template->setValue('PIMPINAN_JABATAN', $dinas->jabatan_pimpinan ?? '');
                $template->setValue('PIMPINAN_PANGKAT', $dinas->pangkat_pimpinan ?? '');
            }

            // Variabel dari data pemohon (JSON field)
            if (is_array($perizinan->data)) {
                foreach ($perizinan->data as $key => $value) {
                    $valStr = is_string($value) ? $value
                        : (is_array($value) ? implode(', ', $value) : json_encode($value));
                    $template->setValue('DATA:' . strtoupper($key), $valStr);
                }
            }

            // Step 2: Simpan DOCX ke file temporary
            $tempDocx = tempnam(sys_get_temp_dir(), 'perizinan_') . '.docx';
            $template->saveAs($tempDocx);

            // Step 3: Baca DOCX → konversi ke HTML via PhpWord HTML Writer
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempDocx);

            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

            // Simpan HTML ke temp file, lalu baca sebagai string
            $tempHtml = tempnam(sys_get_temp_dir(), 'perizinan_') . '.html';
            $htmlWriter->save($tempHtml);
            $rawHtml = file_get_contents($tempHtml);

            // Bersihkan file temp
            @unlink($tempDocx);
            @unlink($tempHtml);

            if (empty($rawHtml)) {
                return null; // fallthrough ke metode biasa
            }

            // Step 4: Inject CSS untuk print (ukuran kertas, font, QR code, watermark)
            $isLandscape = $orientation === 'landscape';
            $fontSize    = $isLandscape ? '9.5pt' : '10.5pt';

            $qrBlock   = $this->buildQrBlock($perizinan);
            $watermark = $this->buildWatermarkBlock($perizinan);

            $printCss = $this->buildPrintCss($paperSize, $orientation, $fontSize);

            // Inject CSS ke dalam <head> yang sudah ada di HTML PhpWord
            $rawHtml = preg_replace('/<\/head>/i', $printCss . '</head>', $rawHtml, 1);

            // Inject watermark & QR sebelum </body>
            $rawHtml = preg_replace('/<\/body>/i', $watermark . $qrBlock . '</body>', $rawHtml, 1);

            return $this->streamPdf($rawHtml, $paperSize, $orientation, $perizinan);

        } catch (\Throwable $e) {
            // Log error tapi jangan crash — fallback ke HTML biasa
            \Illuminate\Support\Facades\Log::warning('GeneratePdfAction: DOCX→PDF gagal, fallback ke HTML. Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Bangun CSS untuk print PDF: ukuran kertas, font, dan page margin.
     */
    private function buildPrintCss(string $paperSize, string $orientation, string $fontSize): string
    {
        $isLandscape = $orientation === 'landscape';

        $size = $paperSize === 'F4'
            ? ($isLandscape ? '330mm 215mm' : '215mm 330mm')
            : strtolower($paperSize) . ' ' . $orientation;

        return '<style>
            @page { size: ' . $size . '; margin: 1.5cm; }
            body {
                font-family: "Times New Roman", Times, serif !important;
                font-size: ' . $fontSize . ' !important;
                line-height: 1.15 !important;
                color: #000 !important;
                margin: 0 !important; padding: 0 !important;
            }
            table { border-collapse: collapse; width: 100% !important; }
            td, th { vertical-align: top; padding: 1px 3px; word-wrap: break-word; }
            p { text-align: justify; margin-top: 0; margin-bottom: 4px; line-height: 1.15 !important; }
            h1, h2, h3, h4 { margin-top: 4px !important; margin-bottom: 4px !important; }
            .qr-block-pdf { text-align: center; margin-top: 10px; }
            .qr-block-pdf img { width: 60px; height: 60px; }
        </style>';
    }

    /**
     * Bangun blok QR code untuk di-inject ke HTML PDF (pakai tabel, bukan position:absolute).
     */
    private function buildQrBlock(Perizinan $perizinan): string
    {
        if (!$perizinan->qr_file) {
            return '';
        }

        $path = storage_path('app/public/qr_codes/' . $perizinan->qr_file);
        if (!file_exists($path)) {
            return '';
        }

        $ext     = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
        $dataUri = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($path));

        return '<div class="qr-block-pdf" style="margin-top:8px;text-align:left;">
            <img src="' . $dataUri . '" style="width:55px;height:55px;" alt="QR Code">
            <div style="font-size:7pt;color:#333;margin-top:2px;">Verifikasi Dokumen</div>
        </div>';
    }

    /**
     * Watermark (center logo) untuk PDF dari DOCX.
     * Dibuat sebagai div absolutely-positioned.
     */
    private function buildWatermarkBlock(Perizinan $perizinan): string
    {
        $dinas = $perizinan->dinas;
        if (!$dinas || !($dinas->watermark_enabled ?? true)) {
            return '';
        }

        $wmPath = $dinas->watermark_img ?: $dinas->logo;
        if (empty($wmPath)) {
            return '';
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($wmPath)) {
            return '';
        }

        $ext     = pathinfo($wmPath, PATHINFO_EXTENSION);
        $data    = \Illuminate\Support\Facades\Storage::disk('public')->get($wmPath);
        $dataUri = 'data:image/' . $ext . ';base64,' . base64_encode($data);
        $opacity = number_format(max(0, min(1, $dinas->watermark_opacity ?? 0.07)), 2);

        return '<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);
            width:180mm;height:180mm;opacity:' . $opacity . ';z-index:-1;pointer-events:none;">
            <img src="' . $dataUri . '" style="width:100%;height:100%;object-fit:contain;" alt="">
        </div>';
    }

    /**
     * Stream PDF via DOMPDF.
     */
    private function streamPdf(string $html, string $paperSize, string $orientation, Perizinan $perizinan): mixed
    {
        $pdf = Pdf::loadHTML($html)
            ->setPaper($this->getPaperDimensions($paperSize), $orientation)
            ->setOptions([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'          => 'Times-Roman',
                'dpi'                  => 96,
                // Override config('dompdf.font_height_ratio') yang mungkin ter-cache di hosting
                // 0.85 memastikan tinggi baris di Linux tidak akan melebihi Windows.
                'fontHeightRatio'      => 0.85,
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

        return [0, 0, min($w, $h), max($w, $h)];
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
