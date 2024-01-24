<?php

use App\Http\Livewire\OpenOrders;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

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

Route::get('tree', [\App\Http\Controllers\PartNumberController::class, 'getPartNumberTree']);

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
    Route::view('label', 'label')->name('label');

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
    Route::get('standard-package-data-upload', [\App\Http\Controllers\StandardPackageController::class, 'dataUpload'])->name('standard-package.data-upload');

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
    Route::get('workcenter-part-number', [\App\Http\Controllers\WorkcenterController::class, 'addPartNumber'])->name('workcenter.part-number');

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
    Route::resource('shift', \App\Http\Controllers\ShiftController::class);

    /**
     *
     */
    Route::resource('part-number', \App\Http\Controllers\PartNumberController::class);
    Route::get('part-number-data-upload', [\App\Http\Controllers\PartNumberController::class, 'dataUpload'])->name('part-number.data-upload');

    /**
     *
     */
    Route::resource('production-plan', \App\Http\Controllers\ProductionPlanController::class);
    Route::get('production-plan-data-upload', [\App\Http\Controllers\ProductionPlanController::class, 'dataUpload'])->name('production-plan.data-upload');
    Route::get('production-plan-disable', [\App\Http\Controllers\ProductionPlanController::class, 'disable'])->name('production-plan.disable');

    /**
     *
     */
    Route::resource('unemployment-record', \App\Http\Controllers\UnemploymentRecordController::class);
    Route::get('unemploymentrecord/record', [\App\Http\Controllers\UnemploymentRecordController::class, 'record'])->name('unemployment-record.record');
    Route::post('unemploymentrecord', [\App\Http\Controllers\UnemploymentRecordController::class, 'save'])->name('unemployment-record.save');
    Route::get('unemployment-report', [\App\Http\Controllers\UnemploymentRecordController::class, 'report'])->name('unemployment-record.report');
    Route::post('unemployment-report/download', [\App\Http\Controllers\UnemploymentRecordController::class, 'download'])->name('unemployment-record.download');

    /**
     *
     */
    Route::resource('type-scrap', \App\Http\Controllers\TypeScrapController::class);

    /**
     *
     */
    Route::resource('scrap', \App\Http\Controllers\ScrapController::class);

    /**
     *
     */
    Route::resource('scrap-record', \App\Http\Controllers\ScrapRecordController::class);
    Route::get('create-scrap', [\App\Http\Controllers\ScrapRecordController::class, 'createScrap'])->name('scrap-record.create-scrap');
    Route::post('store-scrap', [\App\Http\Controllers\ScrapRecordController::class, 'storeScrap'])->name('scrap-record.store-scrap');
    Route::get('scrap-report', [\App\Http\Controllers\ScrapRecordController::class, 'report'])->name('scrap-record.report');
    Route::post('scrap-report/download', [\App\Http\Controllers\ScrapRecordController::class, 'download'])->name('scrap-record.download');

    /**
     *
     */
    Route::resource('prodcution-record', \App\Http\Controllers\ProdcutionRecordController::class);
    Route::get('prodcution-record/{prodcution_record}/reprint', [\App\Http\Controllers\ProdcutionRecordController::class, 'reprint'])->name('prodcution-record.reprint');
    Route::get('prodcution-report', [\App\Http\Controllers\ProdcutionRecordController::class, 'report'])->name('prodcution-record.report');
    Route::post('prodcution-report/download', [\App\Http\Controllers\ProdcutionRecordController::class, 'download'])->name('prodcution-record.download');

    Route::get('clear-pdf-session-data', [\App\Http\Controllers\ProdcutionRecordController::class, 'clearPDFSessionData'])->name('clear-pdf-session-data');

    /**
     *
     */
    Route::resource('status',  \App\Http\Controllers\StatusController::class);

    /**
     *
     */
    Route::resource('examples', \App\Http\Controllers\ExampleController::class);

    /**
     * Routes Permissions
     */
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);

    /**
     * Routes Roles
     */
    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    /**
     * Routes Users
     */
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::get('data', [\App\Http\Controllers\UserController::class, 'data'])->name('data');

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
    Route::get('print', [\App\Http\Controllers\label_printer::class, 'index'])->name('print.index');
 Route::get('print', [\App\Http\Controllers\label_printer::class, 'index'])->name('print.index');
 /**
     * Planeacion prensas
     */
    Route::resource('pressplaning', \App\Http\Controllers\PressPlanningController::class);
    Route::post('period', [\App\Http\Controllers\PressPlanningController::class, 'period'])->name('PressPlanningController.period');


    /**
     * ver Estructura livewird
     */
    Route::get('planeacionview', [\App\Http\Controllers\planeacionviewController::class, 'index'])->name('planeacionview.index');
    Route::get('planeacionview/create', [\App\Http\Controllers\planeacionviewController::class, 'create'])->name('planeacionview.create');
    Route::post('planeacionview/buscar', [\App\Http\Controllers\planeacionviewController::class, 'buscar'])->name('planeacionview.buscar');
    Route::post('planeacionview/create', [\App\Http\Controllers\planeacionviewController::class, 'create'])->name('planeacionview.create');
    Route::post('planeacionview/update', [\App\Http\Controllers\planeacionviewController::class, 'update'])->name('planeacionview.update');
    Route::get('planeacionview/update', [\App\Http\Controllers\planeacionviewController::class, 'create'])->name('planeacionview.update');
    Route::post('planeacionview/updatef1', [\App\Http\Controllers\planeacionviewController::class, 'updateF1'])->name('planeacionview.updatef1');
    Route::get('planeacionview/export', [\App\Http\Controllers\planeacionviewController::class, 'export'])->name('planeacionview.export');
    Route::get('planeacionview/exportfinal', [\App\Http\Controllers\planeacionviewController::class, 'exportfinal'])->name('planeacionview.exportfinal');
    Route::get('planeacionview/exportsubcomponentes', [\App\Http\Controllers\planeacionviewController::class, 'exportsubcomponentes'])->name('planeacionview.exportsubcomponentes');
    Route::post('planeacionview/siguiente', [\App\Http\Controllers\planeacionviewController::class, 'siguiente'])->name('planeacionview.siguiente');
});
