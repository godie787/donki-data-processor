<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NasaApiController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/valid-endpoints', [NasaApiController::class, 'getValidEndpoints']);
Route::get('/instruments', [NasaApiController::class, 'getAllInstruments']);
Route::get('/activity-ids', [NasaApiController::class, 'getAllActivityIDs']);
Route::get('/instrument-usage', [NasaApiController::class, 'getInstrumentUsage']);
Route::post('/instrument-activity', [NasaApiController::class, 'getInstrumentActivityUsage']);
