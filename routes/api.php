<?php

use App\Http\Controllers\CloudinaryUploadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::resource('users', UserController::class);
Route::resource('posts', PostController::class);
Route::post('upload-image', [CloudinaryUploadController::class, 'uploadImages']);
Route::delete('delete-image', [CloudinaryUploadController::class, 'deleteImages']);