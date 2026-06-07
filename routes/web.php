<?php

use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'bio_filled'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/bio', [App\Http\Controllers\BioController::class, 'edit'])->name('bio.edit')->withoutMiddleware('bio_filled');
    Route::patch('/bio', [App\Http\Controllers\BioController::class, 'update'])->name('bio.update')->withoutMiddleware('bio_filled');

    Route::middleware('bio_filled')->group(function () {
        Route::get('/anggota-keluarga/create', [App\Http\Controllers\AnggotaKeluargaController::class, 'create'])->name('anggota-keluarga.create');
        Route::post('/anggota-keluarga', [App\Http\Controllers\AnggotaKeluargaController::class, 'store'])->name('anggota-keluarga.store');
        Route::get('/anggota-keluarga/{anggotaKeluarga}/edit', [App\Http\Controllers\AnggotaKeluargaController::class, 'edit'])->name('anggota-keluarga.edit');
        Route::put('/anggota-keluarga/{anggotaKeluarga}', [App\Http\Controllers\AnggotaKeluargaController::class, 'update'])->name('anggota-keluarga.update');
        Route::delete('/anggota-keluarga/{anggotaKeluarga}', [App\Http\Controllers\AnggotaKeluargaController::class, 'destroy'])->name('anggota-keluarga.destroy');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            Route::get('/kegiatans', [AdminKegiatanController::class, 'index'])->name('kegiatans.index');
            Route::get('/kegiatans/create', [AdminKegiatanController::class, 'create'])->name('kegiatans.create');
            Route::post('/kegiatans', [AdminKegiatanController::class, 'store'])->name('kegiatans.store');
            Route::get('/kegiatans/export-pdf', [AdminKegiatanController::class, 'exportPdf'])->name('kegiatans.exportPdf');
            Route::get('/kegiatans/{kegiatan}', [AdminKegiatanController::class, 'show'])->name('kegiatans.show');
            Route::get('/kegiatans/{kegiatan}/export-pdf', [AdminKegiatanController::class, 'exportPdfDetail'])->name('kegiatans.exportPdfDetail');
            Route::post('/kegiatans/{kegiatan}/notify', [AdminKegiatanController::class, 'notify'])->name('kegiatans.notify');
            Route::get('/kegiatans/{kegiatan}/edit', [AdminKegiatanController::class, 'edit'])->name('kegiatans.edit');
            Route::put('/kegiatans/{kegiatan}', [AdminKegiatanController::class, 'update'])->name('kegiatans.update');
            Route::delete('/kegiatans/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('kegiatans.destroy');

            Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
            Route::get('/whatsapp/status', [WhatsAppController::class, 'status'])->name('whatsapp.status');
            Route::get('/whatsapp/qr', [WhatsAppController::class, 'qr'])->name('whatsapp.qr');
            Route::post('/whatsapp/restart', [WhatsAppController::class, 'restart'])->name('whatsapp.restart');
            Route::post('/whatsapp/send-test', [WhatsAppController::class, 'sendTest'])->name('whatsapp.sendTest');
        });

        Route::middleware('peserta')->group(function () {
            Route::get('/kegiatan', [App\Http\Controllers\KegiatanController::class, 'index'])->name('kegiatan.index');
            Route::get('/kegiatan/{kegiatan}', [App\Http\Controllers\KegiatanController::class, 'show'])->name('kegiatan.show');
            Route::post('/kegiatan/{kegiatan}/respond', [App\Http\Controllers\KegiatanController::class, 'respond'])->name('kegiatan.respond');
        });
    });
});

require __DIR__.'/auth.php';
