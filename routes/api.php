<?php

use App\Http\Controllers\Api\RepositoryApiController;
use Illuminate\Support\Facades\Route;

Route::get('/repository/stats', [RepositoryApiController::class, 'stats'])->name('api.repository.stats');
Route::get('/repository/search', [RepositoryApiController::class, 'search'])->name('api.repository.search');
