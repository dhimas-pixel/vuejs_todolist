<?php

use App\Http\Controllers\ApiTodoListController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('todolist')->group(function () {
    Route::get('list', [ApiTodoListController::class, 'getList'])->name('todolist.list');
    Route::post('create', [ApiTodoListController::class, 'postCreate'])->name('todolist.create');
    Route::post('update/{id}', [ApiTodoListController::class, 'postUpdate'])->name('todolist.update');
    Route::post('delete/{id}', [ApiTodoListController::class, 'postDelete'])->name('todolist.delete');
    Route::get('read/{id}', [ApiTodoListController::class, 'getRead'])->name('todolist.read');
});
