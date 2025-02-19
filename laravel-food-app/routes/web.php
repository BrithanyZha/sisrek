<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

// Dashboard
Route::get('/', [FoodController::class, 'dashboard'])->name('dashboard');

// Form Recommendation by Ingredients
Route::get('/recommendations-form-ingredients', [FoodController::class, 'formRecommendationIngredients'])->name('recommendations-form-ingredients');

// Recommendation Results by Ingredients
Route::post('/recommendations-ingredients', [FoodController::class, 'recommendationByIngredients'])->name('recommendations-ingredients');
