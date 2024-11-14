<?php

use App\Http\Controllers\JobDashboardController;
use App\Http\Controllers\ProfileController;
use App\Jobs\ExampleJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/run-job', function () {
        runBackgroundJob(ExampleJob::class, 'handle', ['param1_value', 'param2_value']);
        return "Job started and logged!";
    });

    Route::get('/job-dashboard', [JobDashboardController::class, 'index'])->name('job.dashboard');
});

require __DIR__.'/auth.php';
