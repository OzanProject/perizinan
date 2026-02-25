<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Statistik Perizinan</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #444;
      padding-bottom: 10px;
    }

    .header h1 {
      margin: 0;
      font-size: 20px;
      color: #0b50da;
    }

    .header p {
      margin: 5px 0 0;
      color: #666;
    }

    .stats-grid {
      width: 100%;
      margin-bottom: 20px;
      border-collapse: collapse;
    }

    .stats-box {
      border: 1px solid #ddd;
      padding: 15px;
      text-align: center;
      width: 25%;
    }

    .stats-box h3 {
      margin: 0;
      font-size: 18px;
      color: #333;
    }

    .stats-box p {
      margin: 5px 0 0;
      font-size: 10px;
      color: #666;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      padding: 10px;
      text-align: left;
      font-size: 10px;
      text-transform: uppercase;
    }

    td {
      border: 1px solid #dee2e6;
      padding: 10px;
      font-size: 11px;
    }

    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 10px;
      color: #999;
      border-top: 1px solid #eee;
      padding-top: 5px;
    }

    .text-center {
      text-align: center;
    }

    .bg-primary {
      color: #0b50da;
    }

    .bg-success {
      color: #10b981;
    }

    .bg-danger {
      color: #ef4444;
    }

    .bg-warning {
      color: #f59e0b;
    }
  </style>
</head>

<body>
  <div class="header" style="border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px;">
    <table width="100%" style="border: none;">
      <tr>
        <td width="80" style="border: none; vertical-align: middle;">
          @if($dinas && $dinas->logo)
            <img src="{{ public_path('storage/' . $dinas->logo) }}" width="65" height="65">
          @else
            <div style="width:65px; height:65px; background:#ddd;"></div>
          @endif
        </td>
        <td style="border: none; text-align: center; vertical-align: middle;">
          <div style="font-size: 16px; font-weight: bold; text-transform: uppercase;">PEMERINTAH KABUPATEN
            {{ strtoupper($dinas->kota ?? 'GARUT') }}</div>
          <div style="font-size: 18px; font-weight: bold; text-transform: uppercase;">
            {{ strtoupper($dinas->app_name ?? 'DINAS PENDIDIKAN') }}</div>
          <div style="font-size: 10px; font-style: italic;">{{ $dinas->alamat ?? '' }}</div>
        </td>
      </tr>
    </table>
  </div>

  <div style="text-align: center; margin-bottom: 20px;">
    <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">LAPORAN STATISTIK PERIZINAN</h3>
    <div style="font-size: 11px; margin-top: 5px;">Periode Laporan: s/d {{ now()->translatedFormat('d F Y') }}</div>
  </div>

  <h4 style="border-left: 5px solid #007bff; padding-left: 10px; margin-bottom: 10px;">I. RINGKASAN DATA</h4>
  <table class="stats-grid">
    <tr>
      <td class="stats-box">
        <h3 class="bg-primary">{{ number_format($stats['total']) }}</h3>
        <p>Total Pengajuan</p>
      </td>
      <td class="stats-box">
        <h3 class="bg-success">{{ number_format($stats['approved']) }}</h3>
        <p>Disetujui</p>
      </td>
      <td class="stats-box">
        <h3 class="bg-danger">{{ number_format($stats['rejected']) }}</h3>
        <p>Ditolak</p>
      </td>
      <td class="stats-box">
        <h3 class="bg-warning">{{ number_format($stats['processing']) }}</h3>
        <p>Diproses</p>
      </td>
    </tr>
  </table>

  <h4 style="border-left: 5px solid #007bff; padding-left: 10px; margin-bottom: 10px; margin-top: 20px;">II. AKTIVITAS
    LEMBAGA</h4>
  <table>
    <thead>
      <tr>
        <th width="30" class="text-center">No</th>
        <th>Nama Lembaga</th>
        <th width="60" class="text-center">Jenjang</th>
        <th width="80" class="text-center">Total</th>
        <th width="60" class="text-center">Selesai</th>
        <th width="60" class="text-center">Proses</th>
        <th width="80" class="text-center">Lulus (%)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($lembagaStats as $index => $lembaga)
        @php
          $persentase = $lembaga->total_pengajuan > 0
            ? round(($lembaga->selesai / $lembaga->total_pengajuan) * 100, 1)
            : 0;
        @endphp
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td>{{ $lembaga->nama_lembaga }}</td>
          <td class="text-center">{{ strtoupper($lembaga->jenjang) }}</td>
          <td class="text-center font-weight-bold">{{ number_format($lembaga->total_pengajuan) }}</td>
          <td class="text-center">{{ $lembaga->selesai }}</td>
          <td class="text-center">{{ $lembaga->proses }}</td>
          <td class="text-center font-weight-bold">{{ $persentase }}%</td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center">Tidak ada data lembaga.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top: 40px; float: right; width: 250px; text-align: center;">
    <div>{{ ($dinas->kota ?? 'Garut') }}, {{ now()->translatedFormat('d F Y') }}</div>
    <div style="margin-top: 5px; font-weight: bold;">Kepala Dinas</div>
    <div style="margin-top: 60px; font-weight: bold; text-decoration: underline;">__________________________</div>
    <div>NIP. ____________________</div>
  </div>

  <div class="footer">
    Dicetak melalui Sistem Perizinan pada {{ now()->format('d/m/Y H:i') }} | Halaman 1 dari 1
  </div>
</body>

</html>