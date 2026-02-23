<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <style>
    @page {
      margin: 25mm;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12pt;
      line-height: 1.6;
      color: #000;
      margin: 0;
      padding: 0;
    }

    .kop {
      width: 100%;
      border-collapse: collapse;
      border-bottom: 4px double #000;
      margin-bottom: 15px;
    }

    .kop td {
      vertical-align: middle;
      padding-bottom: 10px;
    }

    .logo {
      width: 70px;
      height: 70px;
    }

    .kop-text {
      text-align: center;
    }

    .kop-1 {
      font-size: 14pt;
      font-weight: bold;
      text-transform: uppercase;
    }

    .kop-2 {
      font-size: 16pt;
      font-weight: bold;
      text-transform: uppercase;
    }

    .kop-3 {
      font-size: 10pt;
    }

    .judul {
      text-align: center;
      font-weight: bold;
      font-size: 14pt;
      text-decoration: underline;
      text-transform: uppercase;
      margin-top: 15px;
    }

    .nomor {
      text-align: center;
      margin-bottom: 20px;
    }

    .isi {
      text-align: justify;
    }

    .data {
      width: 100%;
    }

    .data td {
      padding: 2px 0;
      vertical-align: top;
    }

    .ttd-table {
      width: 100%;
      margin-top: 40px;
    }

    .ttd-cell {
      text-align: left;
    }

    .qr-block {
      margin-top: 30px;
      text-align: right;
      font-size: 8pt;
    }

    .qr-hash {
      font-family: monospace;
      font-size: 7pt;
    }

    table,
    tr,
    td {
      page-break-inside: avoid;
    }
  </style>
</head>

<body>

  {{-- KOP SURAT --}}
  <table class="kop">
    <tr>
      <td width="90">
        @if($logo)
          <img src="{{ $logo }}" class="logo">
        @endif
      </td>
      <td class="kop-text">
        <div class="kop-1">Pemerintah Kabupaten {{ $dinas->kabupaten ?? 'Garut' }}</div>
        <div class="kop-2">Dinas Pendidikan</div>
        <div class="kop-3">{{ $dinas->alamat ?? '' }}</div>
      </td>
    </tr>
  </table>

  {{-- JUDUL SURAT --}}
  <div class="judul">{{ $jenisPerizinan->nama ?? 'SURAT KETERANGAN' }}</div>
  <div class="nomor">Nomor : {{ $perizinan->nomor_surat ?? '.............................' }}</div>

  {{-- ISI SURAT --}}
  <div class="isi">

    <p><strong>Dasar :</strong></p>
    <ol>
      <li>Surat Permohonan dari Yayasan / Lembaga {{ $lembaga->nama_lembaga ?? $lembaga->nama ?? '-' }}</li>
      <li>Keputusan Kepala Dinas Pendidikan Kabupaten {{ $dinas->kabupaten ?? 'Garut' }}</li>
    </ol>

    <p>
      Berdasarkan hal tersebut diatas, maka dengan ini Kepala Dinas Pendidikan Kabupaten
      {{ $dinas->kabupaten ?? 'Garut' }} menerangkan bahwa :
    </p>

    {{-- DATA DINAMIS dari perizinan_data --}}
    <table class="data">
      @foreach($fieldRows as $row)
        <tr>
          <td width="200">{{ $row['label'] }}</td>
          <td>: {{ $row['value'] }}</td>
        </tr>
      @endforeach

      {{-- Data lembaga otomatis --}}
      <tr>
        <td width="200">NPSN</td>
        <td>: {{ $lembaga->npsn ?? '-' }}</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>: {{ $lembaga->alamat ?? '-' }}</td>
      </tr>
    </table>

    <br>

    <p>
      Keterangan ini berlaku {{ $jenisPerizinan->masa_berlaku_nilai ?? '2' }}
      {{ $jenisPerizinan->masa_berlaku_unit ?? 'Tahun' }}
      dengan ketentuan tidak ada perubahan terhadap penugasan yang ditunjuk oleh yayasan.
    </p>

    <p>
      Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
    </p>

  </div>

  {{-- TANDA TANGAN --}}
  <table class="ttd-table">
    <tr>
      <td width="55%"></td>
      <td class="ttd-cell">
        Dikeluarkan : {{ $dinas->kabupaten ?? 'Garut' }}<br>
        Pada Tanggal :
        {{ $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
        <br><br>
        <strong>{{ strtoupper($dinas->pimpinan_jabatan ?? 'KEPALA DINAS') }}</strong>
        <br><br><br><br>

        <u><strong>{{ $dinas->pimpinan_nama ?? '-' }}</strong></u><br>
        @if($dinas->pimpinan_pangkat)
          {{ $dinas->pimpinan_pangkat }}<br>
        @endif
        NIP. {{ $dinas->pimpinan_nip ?? '-' }}
      </td>
    </tr>
  </table>

  {{-- QR CODE --}}
  @if($qrCode)
    <div class="qr-block">
      <div>Verifikasi Dokumen:</div>
      <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="80">
      <div class="qr-hash">{{ substr($perizinan->document_hash, 0, 16) }}...</div>
    </div>
  @endif

</body>

</html>