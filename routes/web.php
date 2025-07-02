<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

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

// APIs
// REGISTER
Route::get('/api/states',  [LocationController::class, 'states'])->name('api.states');
Route::get('/api/cities',  [LocationController::class, 'cities'])->name('api.cities');

// AUTH and INITIAL SETUP ROUTES

Route::get('/', function () {
    return view('landing-page');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ADDED ROUTES

// RUTAS PROTEGIDAS PARA ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Aqui va la ruta
});

// RUTAS PROTEGIDAS PARA MODERADOR
Route::middleware(['auth', 'role:mod'])->group(function () {
    // Aqui va la ruta
});

// RUTAS PROTEGIDAS PARA USUARIO
Route::middleware(['auth', 'role:user'])->group(function () {
    // Aqui va la ruta
});