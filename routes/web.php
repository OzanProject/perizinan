<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public Verification Route (Anti-Tamper & Immutable)
Route::get('/verify/{hash}', [\App\Http\Controllers\Public\VerificationController::class, 'verify'])->name('perizinan.verify');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('super_admin')) {
            return redirect()->route('super_admin.dashboard');
        }
        return redirect()->route('admin_lembaga.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Lembaga Routes
    Route::group(['prefix' => 'admin-lembaga', 'as' => 'admin_lembaga.', 'middleware' => ['role:admin_lembaga']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');

        // Profil Lembaga
        Route::get('/profil', [\App\Http\Controllers\Backend\AdminLembaga\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profil', [\App\Http\Controllers\Backend\AdminLembaga\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/perizinan', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'index'])->name('perizinan.index');
        Route::get('/perizinan/create', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'create'])->name('perizinan.create');
        Route::get('/perizinan/{perizinan}', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'show'])->name('perizinan.show');
        Route::get('/perizinan/{perizinan}/edit', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'edit'])->name('perizinan.edit');
        Route::put('/perizinan/{perizinan}', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'update'])->name('perizinan.update');
        Route::post('/perizinan/{perizinan}/upload-dokumen', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'uploadDokumen'])->name('perizinan.upload_dokumen');
        Route::delete('/perizinan/{perizinan}/dokumen/{dokumen}', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'deleteDokumen'])->name('perizinan.delete_dokumen');
        Route::post('/perizinan/{perizinan}/update-data', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'updateData'])->name('perizinan.update_data');
        Route::post('/perizinan/{perizinan}/confirm-taken', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'confirmTaken'])->name('perizinan.confirm_taken');
        Route::delete('/perizinan/{perizinan}', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'destroy'])->name('perizinan.destroy');
        Route::post('/perizinan', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'store'])->name('perizinan.store');
        Route::post('/perizinan/{perizinan}/submit', [\App\Http\Controllers\Backend\AdminLembaga\PerizinanController::class, 'submit'])->name('perizinan.submit');
        Route::post('/perizinan/{perizinan}/discussion', [\App\Http\Controllers\Backend\PerizinanDiscussionController::class, 'store'])->name('perizinan.discussion.store');

        // Panduan Sistem
        Route::get('/panduan', [\App\Http\Controllers\Backend\AdminLembaga\GuideController::class, 'index'])->name('guide.index');
    });

    // Super Admin Routes
    Route::group(['prefix' => 'super-admin', 'as' => 'super_admin.', 'middleware' => ['role:super_admin']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Lembaga
        Route::resource('lembaga', \App\Http\Controllers\Backend\SuperAdmin\LembagaController::class);

        Route::get('/perizinan', [\App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'index'])->name('perizinan.index');
        Route::get('/perizinan/{perizinan}', [\App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'show'])->name('perizinan.show');
        Route::get('/perizinan/{perizinan}/finalisasi', [\App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'showFinalisasi'])->name('perizinan.finalisasi');
        Route::post('/perizinan/{perizinan}/release', [\App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'release'])->name('perizinan.release');
        Route::post('perizinan/{perizinan}/approve', [App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'approve'])->name('perizinan.approve');
        Route::post('perizinan/{perizinan}/auto-release', [App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'autoRelease'])->name('perizinan.auto_release');
        Route::post('perizinan/{perizinan}/revision', [App\Http\Controllers\Backend\SuperAdmin\PerizinanController::class, 'needRevision'])->name('perizinan.revision');
        Route::post('/perizinan/{perizinan}/discussion', [\App\Http\Controllers\Backend\PerizinanDiscussionController::class, 'store'])->name('perizinan.discussion.store');

        // Master Data: Jenis Perizinan
        Route::resource('jenis-perizinan', \App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanController::class)->names('jenis_perizinan');
        Route::get('jenis-perizinan/{jenisPerizinan}/template', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanController::class, 'template'])->name('jenis_perizinan.template');
        Route::post('jenis-perizinan/{jenisPerizinan}/template', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanController::class, 'updateTemplate'])->name('jenis_perizinan.template.update');
        Route::get('jenis-perizinan/{jenisPerizinan}/syarat', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanSyaratController::class, 'index'])->name('jenis_perizinan.syarat.index');
        Route::post('jenis-perizinan/{jenisPerizinan}/syarat', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanSyaratController::class, 'store'])->name('jenis_perizinan.syarat.store');
        Route::put('jenis-perizinan/{jenisPerizinan}/syarat/{syarat}', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanSyaratController::class, 'update'])->name('jenis_perizinan.syarat.update');
        Route::delete('jenis-perizinan/{jenisPerizinan}/syarat/{syarat}', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanSyaratController::class, 'destroy'])->name('jenis_perizinan.syarat.destroy');

        // Form Builder
        Route::get('jenis-perizinan/{jenisPerizinan}/form', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanController::class, 'formConfig'])->name('jenis_perizinan.form');
        Route::post('jenis-perizinan/{jenisPerizinan}/form', [\App\Http\Controllers\Backend\SuperAdmin\JenisPerizinanController::class, 'updateFormConfig'])->name('jenis_perizinan.form.update');

        // Pengguna
        Route::resource('users', \App\Http\Controllers\Backend\SuperAdmin\UserController::class);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Backend\SuperAdmin\UserController::class, 'resetPassword'])->name('users.reset_password');

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Backend\SuperAdmin\ReportController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-excel', [\App\Http\Controllers\Backend\SuperAdmin\ReportController::class, 'exportExcel'])->name('laporan.export_excel');
        Route::get('/laporan/export-pdf', [\App\Http\Controllers\Backend\SuperAdmin\ReportController::class, 'exportPdf'])->name('laporan.export_pdf');

        // Penerbitan Sertifikat
        Route::group(['prefix' => 'penerbitan', 'as' => 'penerbitan.'], function () {
            Route::get('/antrian', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'antrian'])->name('antrian');
            Route::get('/riwayat', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'riwayat'])->name('riwayat');
            Route::get('/pusat-cetak', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'pusatCetak'])->name('pusat_cetak');
            Route::get('/{perizinan}/preview', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'preview'])->name('preview');
            Route::get('/{perizinan}/export-pdf', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'exportPdf'])->name('export_pdf');
            Route::get('/{perizinan}/finalisasi', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'finalisasi'])->name('finalisasi');

            // Preset & Layout
            Route::get('/preset', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'presetIndex'])->name('preset.index');
            Route::post('/preset', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'presetStore'])->name('preset.store');
            Route::put('/preset/{preset}', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'presetUpdate'])->name('preset.update');
            Route::delete('/preset/{preset}', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'presetDestroy'])->name('preset.destroy');
            Route::post('/preset/{preset}/set-active', [\App\Http\Controllers\Backend\SuperAdmin\PenerbitanController::class, 'presetSetActive'])->name('preset.set_active');
        });

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/profile', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'updateProfile'])->name('settings.profile.update');
        Route::post('/settings/app', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'updateApp'])->name('settings.app.update');
        Route::post('/settings/security', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'updateSecurity'])->name('settings.security.update');
        Route::post('/settings/cache-clear', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'clearCache'])->name('settings.cache.clear');
        Route::post('/settings/backup', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'backupDb'])->name('settings.backup');
        Route::post('/settings/restore', [\App\Http\Controllers\Backend\SuperAdmin\SettingController::class, 'restoreDb'])->name('settings.restore');
    });
});

require __DIR__ . '/auth.php';
