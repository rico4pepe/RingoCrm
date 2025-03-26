<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegitrationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;


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


Route::get('/tickets', [TicketController::class, 'viewTickets'])->name('tickets.index');



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
    Route::get('/dashboard', [TicketController::class, 'viewTickets'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.view');

    Route::get('/register', [RegitrationController::class, 'showRegistrationForm'])->name('register.form');

    Route::post('/register', [RegitrationController::class, 'registerUser'])->name('register.submit');

    Route::get('/create', [TicketController::class, 'showTicketForm'])->name('tickets.create');

    Route::post('/createtickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'showRespondTicketForm'])->name('tickets.show');

    Route::post('/ticketsreply/{ticket}', [TicketController::class, 'replyToTicket'])->name('tickets.reply');
    Route::get('/tickets/{ticket}/assign', [TicketController::class, 'assignTicket'])->name('tickets.assign');
    Route::put('/tickets/{ticket}/assign', [TicketController::class, 'updateAssign'])->name('tickets.updateAssign');
    Route::post('/tickets/{ticketId}/mark-as-read', [TicketController::class, 'markAsRead'])->name('tickets.markAsRead');

});





Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

