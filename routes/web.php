<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\CompanyGuideController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::resource('companies', CompanyController::class)->middleware('isAdmin');
    // Route::resource('companies.users', CompanyUserController::class)->except('show');
    Route::resource('companies.users', CompanyUserController::class)
        ->except('show')
        ->middleware('isAdmin');
});
    Route::resource('companies.guides', CompanyGuideController::class)->except('show'); 
    Route::resource('companies.activities', CompanyActivityController::class);


require __DIR__.'/auth.php';
