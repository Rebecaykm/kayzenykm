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
});
