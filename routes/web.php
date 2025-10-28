<?php

use App\Http\Controllers\CodeEmailVerifyController;
use App\Http\Controllers\LoginRegController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Hash;
use App\Models\Login;
use App\Http\Controllers\ScanController;
Route::get('/', function () {
    return view('index');
});
Route::post('/scan-file', [ScanController::class, 'scan'])->name('scan.file');
Route::get('/login', [LoginRegController::class, 'showLogin'])->name('show.login');
Route::get('/register', [LoginRegController::class, 'showRegister'])->name('show.register');
Route::post('/login', [LoginRegController::class, 'login'])->name('login');
Route::post('/register', [LoginRegController::class, 'register'])->name('register');


Route::controller(CodeEmailVerifyController::class)->group(function(){

    Route::get('/verify_code', 'showCodeForm')->name('verify.code');
    Route::post('/verify_code', 'submitCode')->name('verify.submit');
    Route::post('/resend_code', 'resendCode')->name('verify.resend');
});

Route::controller(ApplicantController::class)->group(function () {

    Route::get('/applicationform', 'showForm')->name('show.applicationform');
    Route::post('/applicationform', 'store')->name('applicants.store');
    Route::post('/applicants/validate', 'validateField')->name('validate.applicant');
});

Route::controller(BookingController::class)->group(function () {
    Route::get('/Booking', 'index')->name('show.bookingindex');
    Route::post('/booking/store-request', 'storeRequest')->name('booking.storeRequest');
});

Route::controller(ResumeController::class)->group(function(){

    Route::get('/resumeupload', 'showResumeForm')->name('resume.form');
Route::post('/resumeupload', 'upload')->name('resume.upload');
Route::delete('/resumeupload/{resume_format_id}', 'delete')->name('resume.delete');
Route::get('/resumeupload/{resume_format_id}/details', 'details')->name('resume.details');
Route::get('/resumeupload/download', 'download')->name('resume.download');
});
Route::get('/test-session-driver', function () {
    return session()->getHandler() instanceof App\Session\HybridSessionHandler
        ? 'Hybrid driver works!'
        : 'Driver not loaded';
});

// web.php

Route::post('/forgot-password', [LoginRegController::class, 'sendResetOTP'])->name('forgot.password');
Route::post('/forgot-verify', [LoginRegController::class, 'verifyResetOTP'])->name('forgot.verify.ajax');


