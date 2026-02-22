<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Verifikasi Dokumen Digital - {{ config('app.name') }}</title>
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <style>
    :root {
      --primary: #0f172a;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --gray-light: #f8fafc;
      --border: #e2e8f0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Outfit', sans-serif;
      background: #f1f5f9;
      color: var(--primary);
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .container {
      width: 100%;
      max-width: 600px;
      padding: 40px 20px;
    }

    .card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
      padding: 40px;
      text-align: center;
      border: 1px solid var(--border);
      position: relative;
      overflow: hidden;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 100px;
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 24px;
    }

    .valid {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #10b981;
    }

    .invalid {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #ef4444;
    }

    .icon-box {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      margin: 0 auto 24px;
    }

    .icon-valid {
      background: var(--success);
      color: white;
    }

    .icon-invalid {
      background: var(--danger);
      color: white;
    }

    h1 {
      font-size: 24px;
      margin-bottom: 8px;
      font-weight: 700;
    }

    p.subtitle {
      color: #64748b;
      margin-bottom: 32px;
      font-size: 15px;
    }

    .info-grid {
      text-align: left;
      background: var(--gray-light);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 24px;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
      padding-bottom: 12px;
      border-bottom: 1px dotted var(--border);
    }

    .info-item:last-child {
      border: none;
      margin: 0;
      padding: 0;
    }

    .label {
      font-weight: 400;
      color: #64748b;
      font-size: 14px;
    }

    .value {
      font-weight: 600;
      font-size: 14px;
    }

    .hash-box {
      background: #0f172a;
      color: #94a3b8;
      padding: 15px;
      border-radius: 12px;
      font-family: monospace;
      font-size: 12px;
      word-break: break-all;
      text-align: left;
      margin-top: 20px;
    }

    .hash-label {
      font-size: 11px;
      color: #475569;
      margin-bottom: 5px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 14px;
      background: var(--primary);
      color: white;
      text-decoration: none;
      border-radius: 12px;
      font-weight: 600;
      transition: 0.2s;
      margin-top: 20px;
    }

    .btn:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    footer {
      margin-top: auto;
      padding: 40px;
      text-align: center;
      color: #94a3b8;
      font-size: 13px;
    }

    .watermark-bg {
      position: absolute;
      top: -20px;
      right: -20px;
      font-size: 150px;
      color: rgba(0, 0, 0, 0.02);
      pointer-events: none;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="card">
      <div class="watermark-bg"><i class="ion-ribbon-b"></i></div>

      @if(!$success)
        <div class="icon-box icon-invalid">
          <i class="ion-close-round"></i>
        </div>
        <div class="status-badge invalid">DATA TIDAK DITEMUKAN</div>
        <h1>Verifikasi Gagal</h1>
        <p class="subtitle">{{ $message }}</p>
      @else
        @if($isValid)
          <div class="icon-box icon-valid">
            <i class="ion-checkmark-round"></i>
          </div>
          <div class="status-badge valid">DOKUMEN ASLI & VALID</div>
          <h1>Verifikasi Berhasil</h1>
          <p class="subtitle">Dokumen ini terdaftar secara resmi di sistem {{ config('app.name') }}.</p>
        @else
          <div class="icon-box icon-invalid">
            <i class="ion-alert-circled"></i>
          </div>
          <div class="status-badge invalid">TERINDIKASI MODIFIKASI</div>
          <h1>Peringatan Integritas</h1>
          <p class="subtitle">Konten dokumen tidak sesuai dengan tanda tangan digital (Hash Drift).</p>
        @endif

        <div class="info-grid">
          <div class="info-item">
            <span class="label">Jenis Dokumen</span>
            <span class="value">{{ $perizinan->jenisPerizinan->nama }}</span>
          </div>
          <div class="info-item">
            <span class="label">Nomor Surat</span>
            <span class="value">{{ $perizinan->nomor_surat }}</span>
          </div>
          <div class="info-item">
            <span class="label">Pemilik/Lembaga</span>
            <span class="value">{{ $perizinan->lembaga->nama }}</span>
          </div>
          <div class="info-item">
            <span class="label">Tanggal Terbit</span>
            <span class="value">{{ $perizinan->tanggal_terbit->translatedFormat('d F Y') }}</span>
          </div>
          <div class="info-item">
            <span class="label">Status Database</span>
            <span class="value">{{ strtoupper(str_replace('_', ' ', $perizinan->status)) }}</span>
          </div>
        </div>

        <div class="hash-label">SHA-256 Fingerprint (Stored)</div>
        <div class="hash-box">
          {{ $storedHash }}
        </div>

        @if(!$isValid)
          <div class="hash-label" style="margin-top: 20px; color: var(--danger);">SHA-256 Calculated (Modified Content)
          </div>
          <div class="hash-box" style="border: 1px solid var(--danger);">
            {{ $currentHash }}
          </div>
        @endif

        @if($isValid && $perizinan->pdf_path)
          <a href="{{ route('super_admin.penerbitan.export_pdf', $perizinan->id) }}" class="btn">
            <i class="ion-android-download"></i> Unduh Salinan Resmi
          </a>
        @endif
      @endif
    </div>
  </div>

  <footer>
    <div style="margin-bottom: 10px;">
      <img src="{{ asset('storage/' . \App\Models\Dinas::first()?->logo) }}" height="40"
        style="filter: grayscale(100%); opacity: 0.5;">
    </div>
    &copy; {{ date('Y') }} {{ \App\Models\Dinas::first()?->nama_dinas }}. All Rights Reserved.<br>
    Layanan Sertifikat Digital Terenkripsi
  </footer>

</body>

</html>