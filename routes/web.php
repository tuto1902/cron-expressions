<?php

use App\Http\Controllers\CronController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cron', [CronController::class, 'index'])->name('cron.index');
Route::post('/cron/generate', [CronController::class, 'generate'])->name('cron.generate');
