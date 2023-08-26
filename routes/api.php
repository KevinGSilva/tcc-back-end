<?php

use App\Http\Controllers\api\ContractorController;
use App\Http\Controllers\api\EmployeeController;
use App\Http\Controllers\api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:employees')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware(['auth:sanctum']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('employee', EmployeeController::class);
    Route::apiResource('contractor', ContractorController::class);
    Route::apiResource('employee.service', ServiceController::class)->shallow();
});
