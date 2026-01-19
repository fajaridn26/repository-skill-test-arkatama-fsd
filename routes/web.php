<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LapanganBadmintonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

Route::middleware(['auth', 'role:Super Admin'])->name('dashboard')->prefix('/')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
    Route::get('/jadwal', [DashboardController::class, 'jadwalLapangan'])->middleware('auth')->name('dashboard.jadwal');
    Route::get('/filterHari', [DashboardController::class, 'filterHari'])->middleware('auth')->name('dashboard.filterHari');
    Route::get('/filterBulan', [DashboardController::class, 'filterBulan'])->middleware('auth')->name('dashboard.filterBulan');
    Route::get('/filterTahun', [DashboardController::class, 'filterTahun'])->middleware('auth')->name('dashboard.filterTahun');
    Route::get('/grafikPendapatan', [DashboardController::class, 'grafikPendapatan'])->middleware('auth')->name('dashboard.grafikPendapatan');
});

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

Route::middleware(['auth'])->name(
    'profile'
)->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
    Route::put('/{id}', [ProfileController::class, 'edit'])->name('edit');
});

Route::middleware(['auth', 'role:Super Admin'])->name(
    'user'
)->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::post('/import-excel', [UserController::class, 'importExcel'])->name('users.import');
    Route::put('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('/search', [UserController::class, 'search'])->name('users.search');
    Route::put('/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete');
});

Route::middleware(['auth', 'role:Super Admin'])
    ->name('lapangan-badminton')->prefix('lapangan-badminton')
    ->group(function () {
        Route::get('/', [LapanganBadmintonController::class, 'index']);
        Route::post('/', [LapanganBadmintonController::class, 'store']);
        Route::get('/jadwal', [LapanganBadmintonController::class, 'jadwal']);
        Route::get('/search', [LapanganBadmintonController::class, 'search']);
    });

// Route::get('/blank', function () {
//     return view('pages.blank', ['title' => 'Blank']);
// })->name('blank');

// // error pages
// Route::get('/error-404', function () {
//     return view('pages.errors.error-404', ['title' => 'Error 404']);
// })->name('error-404');

// chart pages
// Route::get('/line-chart', function () {
//     return view('pages.chart.line-chart', ['title' => 'Line Chart']);
// })->name('line-chart');

// Route::get('/bar-chart', function () {
//     return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
// })->name('bar-chart');

Route::get('/signin', [UserController::class, 'showLoginForm'])
    ->name('login');

Route::post('/signin', [UserController::class, 'signIn'])
    ->name('login');

Route::get('/signup', [UserController::class, 'showRegisterForm'])
    ->name('signup');

Route::post('/signup', [UserController::class, 'signUp'])
    ->name('signup');

Route::post('/signout', [UserController::class, 'signout'])->name('signout');


// ui elements pages
// Route::get('/alerts', function () {
//     return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
// })->name('alerts');

// Route::get('/avatars', function () {
//     return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
// })->name('avatars');

// Route::get('/badge', function () {
//     return view('pages.ui-elements.badges', ['title' => 'Badges']);
// })->name('badges');

// Route::get('/buttons', function () {
//     return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
// })->name('buttons');

// Route::get('/image', function () {
//     return view('pages.ui-elements.images', ['title' => 'Images']);
// })->name('images');

// Route::get('/videos', function () {
//     return view('pages.ui-elements.videos', ['title' => 'Videos']);
// })->name('videos');

// Route::get('/calendar', function () {
//     return view('pages.calender', ['title' => 'Calendar']);
// })->name('calendar');
