<?php

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
    return redirect()->route('questions.index');
});

// Test routes
Route::get('/test', [App\Http\Controllers\TestController::class, 'start'])->name('test.start');
Route::get('/test/history', [App\Http\Controllers\TestController::class, 'history'])->name('test.history');
Route::post('/test/submit/{testAttempt}', [App\Http\Controllers\TestController::class, 'submit'])->name('test.submit');
Route::post('/test/delete-all', [App\Http\Controllers\TestController::class, 'deleteAllResults'])->name('test.deleteAll');
Route::get('/test/{testAttempt}/result', [App\Http\Controllers\TestController::class, 'result'])->name('test.result');

// Question management routes
Route::resource('questions', App\Http\Controllers\QuestionController::class);
