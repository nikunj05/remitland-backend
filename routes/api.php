<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);
Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::patch('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
