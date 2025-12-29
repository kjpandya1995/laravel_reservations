<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\CompanyGuideController;
use App\Http\Controllers\CompanyActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityRegistrationController;
use App\Http\Controllers\MyActivityController;
use App\Http\Controllers\GuideActivityController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return redirect()->route('my-activity.show');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', HomeController::class)->name('home');
// Route::get('/activities/{activity}',[ActivityController::class, 'show'])->name('activities.show');
Route::get('/activities/{activity}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activity.show');

Route::post('/activities/{activity}/register', [ActivityController::class, 'register'])
    ->name('activities.register');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/activities', [MyActivityController::class, 'show'])->name('my-activity.show');
    Route::delete('/activities/{activity}', [MyActivityController::class, 'destroy'])->name('my-activity.destroy'); 
    Route::get('/guides/activities', [GuideActivityController::class, 'show'])->name('guide-activity.show'); 

Route::post('/users', [UserController::class, 'store']);

// Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::resource('companies', CompanyController::class)->middleware('isAdmin');
    // Route::resource('companies.users', CompanyUserController::class)->except('show');
    Route::resource('companies.users', CompanyUserController::class)
        ->except('show')
        ->middleware('isAdmin');
        Route::resource('companies.guides', CompanyGuideController::class)->except('show'); 
    Route::resource('companies.activities', CompanyActivityController::class);




    Route::get(
    '/companies/{company}/activities',
    [CompanyActivityController::class, 'index']
)->name('companies.activities.index');
});
    




require __DIR__.'/auth.php';
