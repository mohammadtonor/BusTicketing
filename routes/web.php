<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
Route::post('login', [UserAuthController::class, 'user.login.submit']);
Route::post('logout', [UserAuthController::class, 'logout'])->name('user.logout');
Route::get('login/register', [UserAuthController::class, 'showLoginForm'])->name('user.register');
Route::post('login/register', [UserAuthController::class, 'register'])->name('user.register.submit');


Route::get('/', [HomeController::class, 'index']);
Route::get('/search', [HomeController::class, 'search'])->name('schedules');
Route::post('/search', [HomeController::class, 'searchSchedules'])->name('schedules.search');

Route::group(['middleware' => 'auth'], function() {

});


Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Admin Register Routes
    Route::get('register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

    Route::group(['middleware' => 'admin'], function() {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

    Route::resource('buses', BusController::class);
    Route::resource('cities', CityController::class);
    Route::resource('terminals', TerminalController::class);
    Route::resource('routes', RouteController::class);
    Route::resource('schedules', ScheduleController::class);

    });
});


