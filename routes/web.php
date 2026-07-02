<?php

use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/onboarding', [OnboardingController::class, 'create'])->name('onboarding.create');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::middleware('has.profile')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
        Route::get('/programs/custom/create', [ProgramController::class, 'createCustom'])->name('programs.create-custom');
        Route::post('/programs/custom', [ProgramController::class, 'storeCustom'])->name('programs.store-custom');
        Route::get('/programs/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');
        Route::post('/programs/{program:slug}/enroll', [ProgramController::class, 'enroll'])->name('programs.enroll');

        Route::get('/workout/create', [WorkoutController::class, 'create'])->name('workout.create');
        Route::post('/workout', [WorkoutController::class, 'store'])->name('workout.store');
        Route::get('/workout/history', [WorkoutController::class, 'history'])->name('workout.history');
        Route::get('/workout/progress/{exercise:slug}', [WorkoutController::class, 'progress'])->name('workout.progress');

        // Schedule: swap hari & simpan notes (AJAX)
        Route::post('/schedule/swap', [ScheduleController::class, 'swap'])->name('schedule.swap');
        Route::post('/schedule/note', [ScheduleController::class, 'saveNote'])->name('schedule.note');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/exercises', [AdminExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/exercises/create', [AdminExerciseController::class, 'create'])->name('exercises.create');
    Route::post('/exercises', [AdminExerciseController::class, 'store'])->name('exercises.store');
    Route::get('/exercises/{exercise}/edit', [AdminExerciseController::class, 'edit'])->name('exercises.edit');
    Route::put('/exercises/{exercise}', [AdminExerciseController::class, 'update'])->name('exercises.update');
    Route::delete('/exercises/{exercise}', [AdminExerciseController::class, 'destroy'])->name('exercises.destroy');

    Route::get('/programs', [AdminProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [AdminProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [AdminProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}/edit', [AdminProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{program}', [AdminProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{program}', [AdminProgramController::class, 'destroy'])->name('programs.destroy');
});
