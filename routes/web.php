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

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('forms', 'forms')->name('forms');
    Route::view('cards', 'cards')->name('cards');
    Route::view('charts', 'charts')->name('charts');
    Route::view('buttons', 'buttons')->name('buttons');
    Route::view('modals', 'modals')->name('modals');
    Route::view('tables', 'tables')->name('tables');
    Route::view('calendar', 'calendar')->name('calendar');
    Route::view('open-shop-orders', 'open-shop-orders')->name('open-shop-orders');
    Route::view('production', 'production')->name('production');
    Route::view('search', 'search')->name('search');

    /**
     * Routes Open Orders
     */
    Route::get('open-orders', [\App\Http\Controllers\OpenOrderController::class, 'index'])->name('open-orders.index');
    Route::post('open-orders', [\App\Http\Controllers\OpenOrderController::class, 'store'])->name('open-orders.store');

    /**
     * Routes Daily Production
     */
    Route::get('daily-production', [\App\Http\Controllers\DailyProductionController::class, 'index'])->name('daily-production.index');
});
