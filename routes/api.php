<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaUserController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('agenda', AgendaController::class)
        ->only(['store'])
        ->names([
            'store' => 'agenda.store',
        ]);
    Route::post('/agenda/user', [AgendaUserController::class, 'store'])
        ->name('agenda.user.store');
    Route::get('/agenda/user/{id}', [AgendaUserController::class, 'show'])
        ->name('agenda.user.show');
    Route::put('/agenda/user/{id}', [AgendaUserController::class, 'update'])
        ->name('agenda.user.update');
    Route::get('/agenda/user/list/{id}', [AgendaUserController::class, 'list'])
        ->name('agenda.user.list');
    Route::delete('/agenda/user/{id}', [AgendaUserController::class, 'delete'])
        ->name('agenda.user.delete');
});
