<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::group(['middleware' => 'auth:mini-app', 'prefix' => 'mini-app', 'as' => 'mini-app.'], function () {
    Route::apiResource('outlines', Controllers\OutlineController::class);
});
