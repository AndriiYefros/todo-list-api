<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')
    ->name('api.auth.')
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

Route::middleware('auth:sanctum')
    ->prefix('tasks')
    ->name('api.tasks.')
    ->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::put('/{id}', [TaskController::class, 'update'])->name('update');
        Route::patch('/complete/{id}', [TaskController::class, 'complete'])->name('complete');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
    });
