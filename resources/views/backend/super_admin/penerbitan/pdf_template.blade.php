<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Sertifikat - {{ $perizinan->lembaga->nama_lembaga ?? 'Dokumen' }}</title>

  <style>
    /* ==============================================================
       1. RESET KERTAS & BINGKAI FULL
       ============================================================== */
    @page {
      margin: 0px;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 10.5pt;
      /* Font 10.5pt agar tabel muat 1 halaman */
      line-height: 1.15;
      color: #000;
      margin: 0;
      padding: 0;
      width: 100%;
    }

    /* ==============================================================
       2. LAYER BINGKAI & WATERMARK
       ============================================================== */
    .background-layer {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: -2;
    }

    .background-layer img {
      width: 100%;
      height: 100%;
    }

    .watermark-layer {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: -1;
      text-align: center;
    }

    /* ==============================================================
       3. AREA AMAN TEKS (SAFE AREA)
       ============================================================== */
    .kertas-container {
      /* Padding dinamis: menyesuaikan dengan orientasi/preset jika ada */
      padding: 2.5cm 3cm 1.5cm 3cm;
      box-sizing: border-box;
      z-index: 10;
    }

    /* ==============================================================
       4. CSS FIX UNTUK LOGO & SPASI CKEDITOR 4
       ============================================================== */
    figure {
      margin: 0;
      padding: 0;
    }

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

    .image-style-align-left {
      text-align: left !important;
    }

    .image-style-align-left img {
      float: left !important;
      margin-right: 15px !important;
    }

    .image-style-align-center {
      text-align: center !important;
    }

    .image-style-align-center img {
      margin-left: auto !important;
      margin-right: auto !important;
    }

    .image-style-align-right {
      text-align: right !important;
    }

    .image-style-align-right img {
      float: right !important;
      margin-left: 15px !important;
    }

    /* Spasi Paragraf Dipadatkan Anti-Loncat */
    p {
      clear: both;
      margin-top: 0;
      margin-bottom: 4px;
    }

    p:last-child {
      margin-bottom: 0 !important;
    }

    div:last-child {
      margin-bottom: 0 !important;
    }

    /* ==============================================================
       5. FORMAT TABEL & TANDA TANGAN RAPI
       ============================================================== */
    table {
      width: 100% !important;
      border-collapse: collapse;
      page-break-inside: avoid;
    }

    tr {
      page-break-inside: avoid;
      page-break-after: auto;
    }

    table td {
      vertical-align: top;
      padding: 2px 4px;
      border: none;
    }

    .signature-block {
      page-break-inside: avoid;
      margin-top: 5px;
    }

    /* ==============================================================
       6. QR CODE VERIFIKASI (Pojok Kiri Bawah)
       ============================================================== */
    .qr-verification {
      position: fixed;
      bottom: 20mm;
      left: 25mm;
      text-align: center;
      font-size: 8pt;
      font-family: Arial, sans-serif;
      z-index: 20;
    }

    .qr-hash {
      font-family: monospace;
      font-size: 7pt;
      color: #555;
      margin-top: 2px;
    }
  </style>
</head>

<body>

  {{-- LAYER 1: BINGKAI BELAKANG (BORDER) --}}
  @if(($perizinan->jenisPerizinan->use_border ?? false) == 1 && !empty($dinas->watermark_border_img))
    <div class="background-layer" style="opacity: {{ $dinas->watermark_border_opacity ?? 1 }};">
      <?php 
          $borderPath = public_path('storage/' . $dinas->watermark_border_img);
    $base64Border = '';
    if (file_exists($borderPath)) {
      $type = pathinfo($borderPath, PATHINFO_EXTENSION);
      $data = file_get_contents($borderPath);
      $base64Border = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
        ?>
      @if($base64Border)
        <img src="{{ $base64Border }}">
      @endif
    </div>
  @endif

  {{-- LAYER 2: WATERMARK TENGAH (LOGO) --}}
  @if(($dinas->watermark_enabled ?? false) == 1 && !empty($dinas->watermark_img))
    <div class="watermark-layer"
      style="opacity: {{ $dinas->watermark_opacity ?? 0.1 }}; width: {{ $dinas->watermark_size ?? 400 }}px;">
      <?php 
          $wmPath = public_path('storage/' . $dinas->watermark_img);
    $base64Wm = '';
    if (file_exists($wmPath)) {
      $type = pathinfo($wmPath, PATHINFO_EXTENSION);
      $data = file_get_contents($wmPath);
      $base64Wm = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
        ?>
      @if($base64Wm)
        <img src="{{ $base64Wm }}" style="width: 100%; height: auto;">
      @endif
    </div>
  @endif

  {{-- LAYER 3: AREA KONTEN UTAMA DARI EDITOR --}}
  <div class="kertas-container">
    @php
      // Membersihkan tag P kosong di akhir string persis seperti di Controller
      $htmlContent = $perizinan->template_html_final ?? $perizinan->jenisPerizinan->template_html ?? '';
      $htmlContent = preg_replace('/(<p>(&nbsp;|\s|<br\s*\/?>)*<\/p>\s*)+$/i', '', $htmlContent);
    @endphp

    {!! $htmlContent !!}
  </div>

  {{-- LAYER 4: QR CODE VERIFIKASI --}}
  @if(!empty($qrCode))
    <div class="qr-verification">
      <div>Dokumen Sah</div>
      <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="60" height="60" style="margin-top: 5px;">
      <div class="qr-hash">ID: {{ substr($perizinan->document_hash ?? 'XYZ', 0, 10) }}</div>
    </div>
  @endif

</body>

</html>