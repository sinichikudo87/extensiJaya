<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\DashboardController;

// Map
Route::get('/', [MapController::class, 'index'])->name('map');

// Dashboard
Route::get('/dashboard_xx26', [DashboardController::class, 'index'])->name('dashboard');
