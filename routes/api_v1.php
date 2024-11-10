<?php

use App\Http\Controllers\API\V1\LoanController;
use Illuminate\Support\Facades\Route;

// No authentication required for loan retrieval APIs
Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
Route::get('loans/{loan}', [LoanController::class, 'show'])->name('loans.show');

Route::middleware('auth:sanctum')->apiResource('loans', LoanController::class)->except(['show', 'index']);
