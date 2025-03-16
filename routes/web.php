<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegitrationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login form submission
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');


Route::get('/register', [RegitrationController::class, 'showRegistrationForm'])->name('register.form');

Route::post('/register', [RegitrationController::class, 'registerUser'])->name('register.submit');

// // Add this to your routes file
// Route::get('/email/verify', function () {
//     return view('emails.verify-email');
// })->middleware('auth')->name('verification.notice');
Route::get('/email/verify', [VerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

// ✅ Verify email (No 'auth' middleware here!)
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware('signed') // Keep 'signed' to protect the route
    ->name('verification.verify');

// ✅ Protect dashboard for only verified users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.view');
});
