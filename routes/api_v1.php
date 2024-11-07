<?php

use App\Http\Controllers\API\V1\LoanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('loans', LoanController::class);
