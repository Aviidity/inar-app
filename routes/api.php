<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpoonacularController;
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
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/search/recipes/all', [SpoonacularController::class, 'searchRecipes'])->name('searchRecipes');
Route::get('/search/recipes/findByIngredients', [SpoonacularController::class, 'searchRecipesByIngredients'])->name('searchRecipesByIngredients');
Route::get('/search/recipes/{id?}', [SpoonacularController::class, 'getRecipeInformation'])->name('getRecipeInformation');
Route::post('/create/recipe', [SpoonacularController::class, 'createRecipeCard'])->name('createRecipeCard');
