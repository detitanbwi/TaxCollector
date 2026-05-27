<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PajakTagihanController;
use App\Http\Controllers\PenagihController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('pajak/download-template', [PajakTagihanController::class, 'downloadTemplate'])->name('pajak.download-template');
    Route::post('pajak/preview-import', [PajakTagihanController::class, 'previewImport'])->name('pajak.preview-import');
    Route::post('pajak/import', [PajakTagihanController::class, 'import'])->name('pajak.import');
    Route::post('pajak/bulk-delete', [PajakTagihanController::class, 'bulkDelete'])->name('pajak.bulk-delete');
    Route::resource('pajak', PajakTagihanController::class);
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'role:penagih'])->prefix('penagih')->name('penagih.')->group(function () {
    Route::get('/dashboard', [PenagihController::class, 'index'])->name('dashboard');
    Route::post('/search', [PenagihController::class, 'search'])->name('search');
    Route::post('/update-status/{id}', [PenagihController::class, 'updateStatus'])->name('update-status');
    Route::get('/pajak/{id}', [PenagihController::class, 'show'])->name('pajak.show');
});

require __DIR__.'/auth.php';
