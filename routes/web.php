<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

// Main Files List
Route::get('/', [FileController::class, 'index']);

// Create & Store
Route::get('/create', [FileController::class, 'create']);
Route::post('/store', [FileController::class, 'store']);

// Signed URL logic
Route::get('/generate/{file}', [FileController::class, 'generateSignedUrl']);
Route::get('/download/file/{file}', [FileController::class, 'download']);

// Delete, Restore & Force Delete (Using POST/DELETE methods for better security)
Route::delete('/delete/{id}', [FileController::class, 'destroy']);
Route::get('/trash', [FileController::class, 'trash']);
Route::post('/restore/{id}', [FileController::class, 'restore']);
Route::delete('/force-delete/{id}', [FileController::class, 'forceDelete']);