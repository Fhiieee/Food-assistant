<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodScheduleController;

Route::get('/', [FoodScheduleController::class, 'index'])->name('food.index');

Route::post('/food-schedule', [FoodScheduleController::class, 'store'])->name('food.store');

Route::put('/food-schedule/{id}', [FoodScheduleController::class, 'update'])->name('food.update');

Route::patch('/food-schedule/{id}/status', [FoodScheduleController::class, 'updateStatus'])->name('food.status');

Route::delete('/food-schedule/{id}', [FoodScheduleController::class, 'destroy'])->name('food.destroy');
