<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Comment\Http\Controllers\CommentController;

Route::resource('comments',CommentController::class);

Route::patch('comments/{comment}/approve',[CommentController::class,'approve'])->name('comments.approve');
Route::patch('comments/{comment}/reject',[CommentController::class,'reject'])->name('comments.reject');
