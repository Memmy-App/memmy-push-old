<?php

use App\Http\Controllers\PushController;
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

Route::post("/push/status", [PushController::class, "getStatus"]);
Route::post("/push/enable", [PushController::class, "enabled"]);
Route::post("/push/disable", [PushController::class, "disable"]);
