@extends('layouts.backend')

@section('title', 'Profil Pengguna')
@section('breadcrumb', 'Profil')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-user-circle mr-2 text-primary"></i> Pengaturan
                    Akun</h1>
                <p class="text-muted small mt-1">Kelola informasi profil pribadi, keamanan kata sandi, dan preferensi akun
                    Anda.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Breadcrumbs / Back button if needed -->
                <div class="mb-4">
                    <a href="{{ url()->previous() }}" class="btn btn-default btn-sm shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>

                <!-- Profile Summary (Mini Card) -->
                <div class="card card-widget widget-user-2 shadow-sm mb-4">
                    <div class="widget-user-header bg-primary" style="border-radius: 1rem 1rem 0 0;">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=EBF4FF&color=7F9CF5"
                                alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username font-weight-bold ml-2">{{ $user->name }}</h3>
                        <h5 class="widget-user-desc ml-2">{{ $user->email }}</h5>
                    </div>
                </div>

                <!-- Identitas Personal -->
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-info-circle mr-2 text-primary"></i> Informasi Personal
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Keamanan / Password -->
                <div class="card card-outline card-warning shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-lock mr-2 text-warning"></i> Keamanan & Password
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card card-outline card-danger shadow-sm border-danger">
                    <div class="card-header bg-danger-soft">
                        <h3 class="card-title font-weight-bold text-danger">
                            <i class="fas fa-exclamation-circle mr-2"></i> Zona Berbahaya
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.05);
        }

        .widget-user-2 .widget-user-header {
            padding: 1.5rem;
        }

        .widget-user-2 .widget-user-image>img {
            width: 65px;
            height: 65px;
        }

        .widget-user-2 .widget-user-username,
        .widget-user-2 .widget-user-desc {
            margin-left: 80px;
        }
    </style>
@endsection