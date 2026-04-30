<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', [FileController::class, 'index']);
Route::get('/create', [FileController::class, 'create']);
Route::post('/store', [FileController::class, 'store']);

Route::get('/generate/{file}', [FileController::class, 'generateSignedUrl']);
Route::get('/download/file/{file}', [FileController::class, 'download']);

Route::get('/delete/{id}', [FileController::class, 'destroy']);
Route::get('/trash', [FileController::class, 'trash']);
Route::get('/restore/{id}', [FileController::class, 'restore']);
Route::get('/force-delete/{id}', [FileController::class, 'forceDelete']);