<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampanaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// Flujo 1 · Acceso (solo invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
});

// Rutas protegidas con el middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('productos.index'));
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');

    // Flujo 2 · Productos (CRUD)
    Route::resource('productos', ProductoController::class);

    // Flujo 3 · Generar campaña con IA (anidada en el producto)
    Route::get('/generar', [CampanaController::class, 'elegirProducto'])->name('campanas.elegir');
    Route::get('/productos/{producto}/campanas/nueva', [CampanaController::class, 'create'])->name('campanas.create');
    Route::post('/productos/{producto}/campanas', [CampanaController::class, 'store'])
        ->middleware('throttle:groq')->name('campanas.store');

    // Flujo 4 · Historial
    Route::get('/campanas', [CampanaController::class, 'index'])->name('campanas.index');
    Route::get('/campanas/{campana}', [CampanaController::class, 'show'])->name('campanas.show');
    Route::get('/campanas/{campana}/editar', [CampanaController::class, 'edit'])->name('campanas.edit');
    Route::put('/campanas/{campana}', [CampanaController::class, 'update'])->name('campanas.update');
    Route::post('/campanas/{campana}/regenerar', [CampanaController::class, 'regenerate'])
        ->middleware('throttle:groq')->name('campanas.regenerate');
    Route::post('/campanas/{campana}/restaurar', [CampanaController::class, 'restore'])->name('campanas.restore');
    Route::delete('/campanas/{campana}', [CampanaController::class, 'destroy'])->name('campanas.destroy');
});

// TEMPORAL: diagnóstico de despliegue (se elimina después)
Route::get('/diag-7f3k9q', function () {
    $out = ['php' => PHP_VERSION, 'pdo_pgsql' => extension_loaded('pdo_pgsql'), 'db_url_set' => filled(env('DB_URL')), 'conn' => config('database.default'), 'app_key' => filled(config('app.key'))];
    try { $out['db'] = \Illuminate\Support\Facades\DB::select('select count(*) as n from users')[0]->n; } catch (\Throwable $e) { $out['db_error'] = $e->getMessage(); }
    try { session()->put('x', 1); $out['session'] = 'ok'; } catch (\Throwable $e) { $out['session_error'] = $e->getMessage(); }
    return response()->json($out);
})->withoutMiddleware([\Illuminate\Session\Middleware\StartSession::class, \Illuminate\View\Middleware\ShareErrorsFromSession::class, \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
