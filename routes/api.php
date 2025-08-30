<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TesteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//    return $request->user();
// })->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);

// Route::get('/invoices', [InvoiceController::class, 'index']);
// Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
// Route::post('/invoices', [InvoiceController::class, 'store']);
// Route::put('/invoices/{invoice}', [InvoiceController::class, 'update']);
// Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);

Route::apiResource('invoices', InvoiceController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route::get('/teste', [TesteController::class, 'index'])->middleware('auth:sanctum');
// Route::get('/teste', [TesteController::class, 'index'])->middleware(['auth:sanctum', 'abilities:invoices-index,invoices-show']); // todos tem que passar
// Route::get('/teste', [TesteController::class, 'index'])->middleware(['auth:sanctum', 'ability:invoices-index,invoices-show']); // se apenas um ser válido já passa


