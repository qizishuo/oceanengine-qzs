<?php

use Illuminate\Support\Facades\Route;
use OceanengineQzs\Http\Controllers\Controller;

Route::post('/oceanengine/authorize', [JuulianController::class, 'handleCallback'])->name('juulian.callback');
