<?php

use App\Http\Controllers\Frontend\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversalPdfController;

// 1. Root Redirection
    Route::get('/', function () {
        return view('homepage');
    })->name('homepage');


// 2. EXCLUDED ROUTES: Dashboard, Profile, Auth, and Admin routes
// These routes will NOT have the 'vikas' prefix.

Route::get('{any}', [UniversalPdfController::class, 'handle'])
    ->where('any', '.*\.pdf$');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php'; // Breeze authentication routes
require __DIR__ . '/admin.php'; // Admin routes

// 3. PUBLIC ROUTES GROUP: Applying the public URLs
Route::post('/send-otp', [LeadController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [LeadController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/submit-admission', [LeadController::class, 'submitAdmission'])->name('submit.admission');
Route::post('/submit-enquiry', [LeadController::class, 'submitEnquiry'])->name('submit.enquiry');

// Institution Routes
Route::get('/institutions', [\App\Http\Controllers\Frontend\InstitutionFrontendController::class, 'index'])->name('institutions.list');
Route::get('/institution/{slug}', [\App\Http\Controllers\Frontend\InstitutionFrontendController::class, 'show'])->name('institutions.show');

// URL: /{slug} (Dynamic Pages)
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^[a-zA-Z0-9\-_/]+$')
    ->name('page.view');
