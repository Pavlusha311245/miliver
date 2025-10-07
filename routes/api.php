<?php

declare(strict_types=1);

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('projects', ProjectController::class);

Route::get('/user', static function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
