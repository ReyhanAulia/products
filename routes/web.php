<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload-form', function () {
    return view('upload'); // Mengarahkan ke view upload.blade.php
});

Route::post('/upload', [UploadController::class, 'upload'])->name('upload');


