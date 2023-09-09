<?php

use App\Http\Livewire\OpenOrders;
use Faker\Guesser\Name;
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
    Route::resource('measurement', \App\Http\Controllers\MeasurementController::class);

    /**
     *
     */
    Route::resource('type', \App\Http\Controllers\TypeController::class);

    /**
     *
     */
    Route::resource('item-class', \App\Http\Controllers\ItemClassController::class);
    Route::get('item-class-data-upload', [\App\Http\Controllers\ItemClassController::class, 'dataUpload'])->name('item-class.data-upload');

    /**
     *
     */
    Route::resource('standard-package', \App\Http\Controllers\StandardPackageController::class);

    /**
     *
     */
    Route::resource('planner', \App\Http\Controllers\PlannerController::class);
    Route::get('planner-data-upload', [\App\Http\Controllers\PlannerController::class, 'dataUpload'])->name('planner.data-upload');

    /**
     *
     */
    Route::resource('client', \App\Http\Controllers\ClientController::class);

    /**
     *
     */
    Route::resource('project', \App\Http\Controllers\ProjectController::class);

    /**
     *
     */
    Route::resource('workcenter', \App\Http\Controllers\WorkcenterController::class);
    Route::get('workcenter-data-upload', [\App\Http\Controllers\WorkcenterController::class, 'dataUpload'])->name('workcenter.data-upload');

    /**
     *
     */
    Route::resource('departament', \App\Http\Controllers\DepartamentController::class);

    /**
     *
     */
    Route::resource('unemployment-type', \App\Http\Controllers\UnemploymentTypeController::class);

    /**
     *
     */
    Route::resource('unemployment', \App\Http\Controllers\UnemploymentController::class);

    /**
     *
     */
    Route::resource('part-number', \App\Http\Controllers\PartNumberController::class);

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
    Route::post('planeacion/buscar', [\App\Http\Controllers\PlaneacionController::class, 'buscar'])->name('planeacion.buscar');
    Route::post('planeacion/create', [\App\Http\Controllers\PlaneacionController::class, 'create'])->name('planeacion.create');
    Route::post('planeacion/update', [\App\Http\Controllers\PlaneacionController::class, 'update'])->name('planeacion.update');
    Route::get('planeacion/update', [\App\Http\Controllers\PlaneacionController::class, 'create'])->name('planeacion.update');
    Route::post('planeacion/updatef1', [\App\Http\Controllers\PlaneacionController::class, 'updateF1'])->name('planeacion.updatef1');
    Route::get('planeacion/export', [\App\Http\Controllers\PlaneacionController::class, 'export'])->name('planeacion.export');
    Route::get('planeacion/exportfinal', [\App\Http\Controllers\PlaneacionController::class, 'exportfinal'])->name('planeacion.exportfinal');
    Route::get('planeacion/exportsubcomponentes', [\App\Http\Controllers\PlaneacionController::class, 'exportsubcomponentes'])->name('planeacion.exportsubcomponentes');
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

    /**
     * ver Estructura livewird
     */
    // Route::get('Search', [\App\Http\Livewire\Search::class, 'render'])->name('search');
    // Route::get('print', [\App\Http\Controllers\label_printer::class, 'index'])->name('print.index');
});
