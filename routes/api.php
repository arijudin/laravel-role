<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::name('api.')->group(function () {
  
  // Users
  Route::prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])
    ->name('get');

    Route::put('/{id}', [UserController::class, 'update'])
    ->name('update');

    Route::delete('/{id}', [UserController::class, 'destroy'])
    ->name('delete');

    Route::get('/export-excel', [UserController::class, 'exportExcl'])
    ->name('export_excel');

    Route::get('/export-pdf', [UserController::class, 'exportPdf'])
    ->name('export_pdf');
  });
});