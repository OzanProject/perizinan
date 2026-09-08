<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden - Akses Ditolak</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte3/adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte3/adminlte3/dist/css/adminlte.min.css') }}">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .error-container {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
            border-top: 4px solid #f39c12;
        }
        .error-code {
            font-size: 80px;
            font-weight: 700;
            color: #f39c12;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: #343a40;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .error-message {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            background-color: #0056b3;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">403</h1>
        <h3 class="error-title">
            <i class="fas fa-exclamation-triangle text-warning mr-2"></i> 
            Akses Ditolak (Forbidden)
        </h3>
        <p class="error-message">
            Maaf, Anda tidak memiliki izin atau otoritas yang cukup untuk mengakses halaman atau memproses data ini. 
            <br><br>
            <i>Silakan hubungi Super Admin jika Anda merasa ini adalah sebuah kesalahan.</i>
        </p>
        <a href="javascript:history.back()" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
        </a>
        
        <div class="mt-4">
            <a href="{{ url('/') }}" class="text-muted" style="text-decoration: underline; font-size: 14px;">Atau ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>
