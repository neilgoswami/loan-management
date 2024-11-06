<?php

use App\Http\Controllers\API\V1\LoanController;
use Illuminate\Support\Facades\Route;

Route::apiResource('loans', LoanController::class);
