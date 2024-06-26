<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Models\Chat;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('chats', ChatController::class);
    Route::post('/chats/{chat}/add-user', [ChatController::class, 'addUser'])->name('chats.addUser');
    Route::redirect('/', 'chats');
});

require __DIR__.'/auth.php';
