<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [FileUploadController::class, 'store']);
Route::delete('/files/{id}', [FileUploadController::class, 'destroy']);
Route::get('/files/{refTable}/{refId}', [FileUploadController::class, 'getFiles']);