<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeasurementsController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PatientsListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelectedPatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Middleware\RequireSelectedPatientMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/patients');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/patients', PatientsListController::class)->name('patients');
    Route::get('/patients/select/{patient}', [SelectedPatientController::class, 'store']);
    Route::delete('/patients/select', [SelectedPatientController::class, 'destroy']);

    Route::middleware([RequireSelectedPatientMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/overview', [OverviewController::class, 'index'])->name('overview');
        Route::get('/measurements', [MeasurementsController::class, 'index'])->name('measurements');
        Route::get('/treatment', [TreatmentController::class, 'index'])->name('treatment');
    });
});
