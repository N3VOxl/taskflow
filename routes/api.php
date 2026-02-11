<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public Routes (No login required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Login required)
// This satisfies: "Protected routes (both frontend and backend)" [cite: 7]
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- Rutele Noi pentru Workspace ---
    // Asta creează automat toate rutele (index, store, show, destroy)
    Route::apiResource('workspaces', \App\Http\Controllers\Api\WorkspaceController::class);
    Route::get('/workspaces/{workspace}/boards', [\App\Http\Controllers\Api\BoardController::class, 'index']);
    Route::post('/workspaces/{workspace}/boards', [\App\Http\Controllers\Api\BoardController::class, 'store']);
    // Rute pentru Lists (Coloane)
    Route::get('/boards/{board}/lists', [\App\Http\Controllers\Api\TaskListController::class, 'index']);
    Route::post('/boards/{board}/lists', [\App\Http\Controllers\Api\TaskListController::class, 'store']);

    // Rute pentru Cards (Task-uri)
    Route::post('/lists/{list}/cards', [\App\Http\Controllers\Api\CardController::class, 'store']);
    // Update (Move) Card
    Route::patch('/cards/{card}', [\App\Http\Controllers\Api\CardController::class, 'update']);
    
    // Delete Card
    Route::delete('/cards/{card}', [\App\Http\Controllers\Api\CardController::class, 'destroy']);
});