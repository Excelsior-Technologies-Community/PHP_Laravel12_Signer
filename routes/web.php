<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;


Route::get('/', [FileController::class, 'index']);
Route::get('/generate/{file}', [FileController::class, 'generateSignedUrl']);
Route::get('/download/file/{file}', [FileController::class, 'download']);
