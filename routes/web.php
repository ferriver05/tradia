<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\GarajeController;
use App\Http\Controllers\VitrinaController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;

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

Route::middleware('auth')->group(function () {
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

Route::get('/inicio', [DashboardController::class, 'index'])
    ->name(name: 'user.dashboard');

// RUTAS DE ITEMS
Route::resource('garaje', GarajeController::class)
    ->parameters(['garaje' => 'item']);

Route::patch('/garaje/{item}/pausar', [GarajeController::class, 'pause'])
    ->name('garaje.pause');

Route::patch('/garaje/{item}/reactivar', [GarajeController::class, 'reactivate'])
    ->name('garaje.reactivate'); 

Route::resource('vitrina', VitrinaController::class)
    ->parameters(['vitrina' => 'item']);

// RUTAS DE REQUESTS EXCHANGES
Route::resource('intercambios', ExchangeRequestController::class);

Route::get('/intercambios/{requestedItem}/elegir-objeto', [ExchangeRequestController::class, 'chooseItem'])
    ->name('exchange-requests.choose-item');

Route::patch('/intercambios/{exchangeRequest}/aceptar', [ExchangeRequestController::class, 'accept'])
    ->name('intercambios.accept');

Route::patch('/intercambios/{exchangeRequest}/rechazar', [ExchangeRequestController::class, 'reject'])
    ->name('intercambios.reject');

Route::patch('/intercambios/{exchangeRequest}/cancelar', [ExchangeRequestController::class, 'cancel'])
    ->name('intercambios.cancel');

Route::patch('/intercambios/{exchangeRequest}/match/confirmar', [ExchangeRequestController::class, 'confirmarMatch'])
    ->name('intercambios.match.confirmar');

Route::patch('/intercambios/{exchangeRequest}/match/cancelar', [ExchangeRequestController::class, 'cancelarMatch'])
    ->name('intercambios.match.cancelar');

Route::delete('/intercambios/{exchangeRequest}/match/confirmar', [ExchangeRequestController::class, 'revertirConfirmacion'])
    ->name('intercambios.match.confirmar.revertir');
    
Route::delete('/intercambios/{exchangeRequest}/match/cancelar', [ExchangeRequestController::class, 'revertirCancelacion'])
    ->name('intercambios.match.cancelar.revertir');

Route::patch('/intercambios/{exchangeRequest}/match/rechazar', [ExchangeRequestController::class, 'rechazarPropuesta'])
    ->name('intercambios.match.propuesta.rechazar');

Route::get('/chats', [ChatController::class, 'index'])
    ->name('chats.index');

Route::get('/chats/{chat}', [ChatController::class, 'show'])
    ->name('chats.show');

Route::post('/chats/{id}/mensaje', [ChatController::class, 'store'])
    ->name('chats.store');

Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');

Route::get('/perfil/password', [ProfileController::class, 'editPassword'])
    ->name('profile.password');

Route::post('/perfil/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password.update');

Route::get('/perfil/{user:alias}', [ProfileController::class, 'show'])
    ->name('profile.show');