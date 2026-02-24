<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Tanda Terima Pengajuan - {{ $perizinan->id }}</title>
  <style>
    @page {
      margin: 2cm;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.5;
      color: #333;
    }

    .header {
      text-align: center;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .logo {
      float: left;
      width: 70px;
      height: 70px;
    }

    .header-text {
      margin-left: 80px;
      margin-right: 80px;
    }

    .header-text h2,
    .header-text h3 {
      margin: 0;
      text-transform: uppercase;
    }

    .content {
      margin-top: 30px;
    }

    .title {
      text-align: center;
      font-weight: bold;
      font-size: 14pt;
      text-decoration: underline;
      margin-bottom: 30px;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    table td {
      vertical-align: top;
      padding: 5px 0;
    }

    .footer {
      margin-top: 50px;
    }

    .signature {
      float: right;
      width: 250px;
      text-align: center;
    }

    .qr-code {
      margin-top: 20px;
      text-align: center;
    }

    .clear {
      clear: both;
    }

    .watermark {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.1;
      z-index: -1;
      width: 300px;
    }
  </style>
</head>

<body>
  @php
    $dinas = $perizinan->dinas;
    $logoBase64 = null;
    if ($dinas && $dinas->logo && Storage::disk('public')->exists($dinas->logo)) {
      $logoBase64 = base64_encode(Storage::disk('public')->get($dinas->logo));
      $logoType = pathinfo(Storage::disk('public')->path($dinas->logo), PATHINFO_EXTENSION);
    }
  @endphp

  <div class="header">
    @if($logoBase64)
      <img src="data:image/{{ $logoType }};base64,{{ $logoBase64 }}" class="logo">
    @endif
    <div class="header-text">
      <h2>PEMERINTAH KABUPATEN {{ strtoupper($dinas->kabupaten ?? 'GARUT') }}</h2>
      <h3>DINAS PENDIDIKAN</h3>
      <p style="margin: 0; font-size: 10pt;">{{ $dinas->alamat ?? 'Jl. Jenderal Sudirman No. 1' }}</p>
    </div>
    <div class="clear"></div>
  </div>

  <div class="content">
    <div class="title">Tanda Terima Pengajuan Perizinan</div>

    <p>Telah diterima berkas pengajuan perizinan dengan rincian sebagai berikut:</p>

    <table>
      <tr>
        <td width="180">Nomor Pengajuan</td>
        <td width="10">:</td>
        <td style="font-weight: bold;">#{{ $perizinan->id }}</td>
      </tr>
      <tr>
        <td>Tanggal Pengajuan</td>
        <td>:</td>
        <td>{{ $perizinan->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
      </tr>
      <tr>
        <td>Nama Lembaga</td>
        <td>:</td>
        <td style="font-weight: bold;">{{ $perizinan->lembaga->nama_lembaga }}</td>
      </tr>
      <tr>
        <td>NPSN</td>
        <td>:</td>
        <td>{{ $perizinan->lembaga->npsn }}</td>
      </tr>
      <tr>
        <td>Jenis Perizinan</td>
        <td>:</td>
        <td style="font-weight: bold;">{{ $perizinan->jenisPerizinan->nama }}</td>
      </tr>
      <tr>
        <td>Status Terakhir</td>
        <td>:</td>
        <td>{{ \App\Enums\PerizinanStatus::from($perizinan->status)->label() }}</td>
      </tr>
    </table>

    <p>Dokumen ini merupakan bukti resmi pendaftaran perizinan melalui Sistem Informasi Perizinan Dinas. Harap simpan
      dokumen ini atau bawa saat melakukan koordinasi atau pengambilan dokumen fisik ke Dinas Pendidikan.</p>
  </div>

  <div class="footer">
    <div class="signature">
      <p>{{ $dinas->kabupaten ?? 'Garut' }}, {{ date('d F Y') }}</p>
      <p style="margin-top: 80px; font-weight: bold; text-decoration: underline;">Tanda Terima Sistem</p>
      <p>Dicetak secara otomatis</p>
    </div>
    <div class="clear"></div>
  </div>

  <div class="qr-code">
    <!-- Placeholder for future QR if needed -->
    <p style="font-size: 8pt; color: #999;">ID Transaksi: {{ hash('crc32', $perizinan->id . $perizinan->created_at) }}
    </p>
  </div>
</body>

</html>