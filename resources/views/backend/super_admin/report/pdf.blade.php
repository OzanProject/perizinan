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
  <div class="header">
    <h1>LAPORAN STATISTIK PERIZINAN</h1>
    <p>Dinas Pendidikan - Sistem Informasi Perizinan PKBM</p>
    <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
  </div>

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

  <h3>Aktivitas Lembaga</h3>
  <table>
    <thead>
      <tr>
        <th width="30">No</th>
        <th>Nama Lembaga</th>
        <th width="60">Jenjang</th>
        <th width="80" class="text-center">Total</th>
        <th width="60" class="text-center">Selesai</th>
        <th width="60" class="text-center">Proses</th>
        <th width="80" class="text-center">Lulus (%)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lembagaStats as $index => $lembaga)
        @php
          $persentase = $lembaga->total_pengajuan > 0
            ? round(($lembaga->selesai / $lembaga->total_pengajuan) * 100, 1)
            : 0;
        @endphp
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td>{{ $lembaga->nama_lembaga }}</td>
          <td class="text-center">{{ strtoupper($lembaga->jenjang) }}</td>
          <td class="text-center">{{ number_format($lembaga->total_pengajuan) }}</td>
          <td class="text-center">{{ $lembaga->selesai }}</td>
          <td class="text-center">{{ $lembaga->proses }}</td>
          <td class="text-center">{{ $persentase }}%</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="footer">
    © {{ date('Y') }} Sistem Perizinan Dinas - Laporan ini digenerate secara otomatis oleh sistem.
  </div>
</body>

</html>