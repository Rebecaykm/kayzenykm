<?php

use App\Http\Livewire\OpenOrders;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/', function () {
        return view('dashboard');
    });
    Route::view('forms', 'forms')->name('forms');
    Route::view('cards', 'cards')->name('cards');
    Route::view('charts', 'charts')->name('charts');
    Route::view('buttons', 'buttons')->name('buttons');
    Route::view('modals', 'modals')->name('modals');
    Route::view('tables', 'tables')->name('tables');
    Route::view('calendar', 'calendar')->name('calendar');
    Route::view('production', 'production')->name('production');

    /**
     *
     */
    Route::resource('examples', \App\Http\Controllers\ExampleController::class);

    /**
     * Routes Roles
     */
    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    /**
     * Routes Users
     */
    Route::resource('users', \App\Http\Controllers\UserController::class);

    /**
     * Routes Open Orders
     */
    Route::get('open-orders', [\App\Http\Controllers\OpenOrderController::class, 'index'])->name('open-orders.index');
    Route::post('open-orders', [\App\Http\Controllers\OpenOrderController::class, 'store'])->name('open-orders.store');

    /**
     * Routes Daily Production
     */
    Route::get('daily-production', [\App\Http\Controllers\DailyProductionController::class, 'index'])->name('daily-production.index');
    Route::get('daily-production-user', [\App\Http\Controllers\DailyProductionController::class, 'indexUser'])->name('daily-production.user');
    Route::post('daily-production', [\App\Http\Controllers\DailyProductionController::class, 'store'])->name('daily-production.store');

    /**
     * Routes planeacion
     */
    Route::get('planeacion', [\App\Http\Controllers\PlaneacionController::class, 'index'])->name('planeacion.index');
    Route::get('planeacion/create', [\App\Http\Controllers\PlaneacionController::class, 'create'])->name('planeacion.create');
    Route::post('planeacion/create', [\App\Http\Controllers\PlaneacionController::class, 'create'])->name('planeacion.create');
    Route::post('planeacion/update', [\App\Http\Controllers\PlaneacionController::class, 'update'])->name('planeacion.update');
    Route::get('planeacion/update', [\App\Http\Controllers\PlaneacionController::class, 'create'])->name('planeacion.update');
    Route::get('planeacion/export', [\App\Http\Controllers\PlaneacionController::class, 'export'])->name('planeacion.export');
    Route::get('planeacion/exportfinal', [\App\Http\Controllers\PlaneacionController::class, 'exportfinal'])->name('planeacion.exportfinal');
    Route::post('planeacion/siguiente', [\App\Http\Controllers\PlaneacionController::class, 'siguiente'])->name('planeacion.siguiente');
    /**
     * Cargar Estructura BOM
     */
    Route::get('Structure', [\App\Http\Controllers\Structure::class, 'index'])->name('Structure.index');
    Route::post('Structure', [\App\Http\Controllers\Structure::class, 'index'])->name('Structure.index');

    /**
     * Cargar Buscar
     */
    Route::get('Buscar', [\App\Http\Controllers\BuscarController::class, 'index'])->name('Buscar.index');
    Route::post('Buscar', [\App\Http\Controllers\BuscarController::class, 'index'])->name('Buscar.index');
    Route::post('Buscar/search', [\App\Http\Controllers\BuscarController::class, 'create'])->name('Buscar.create');
    Route::post('Buscar/update', [\App\Http\Controllers\BuscarController::class, 'update'])->name('Buscar.update');
    /**
     * ver Estructura BOM
     */
    Route::get('ShowStructure', [\App\Http\Controllers\showStructure::class, 'index'])->name('Showtructure.index');
    Route::post('ShowStructure', [\App\Http\Controllers\showStructure::class, 'index'])->name('ShowStructure.index');
    Route::post('ShowStructure/update', [\App\Http\Controllers\showStructure::class, 'update'])->name('ShowStructure.update');
    Route::get('ShowStructure', [\App\Http\Controllers\showStructure::class, 'index'])->name('ShowStructure.update');
    Route::get('ShowStructure/export', [\App\Http\Controllers\showStructure::class, 'export'])->name('ShowStructure.export');
});
