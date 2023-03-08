<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\LandingController;


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

Route::get('/', function () { return view('welcome'); })->name('landing');
Route::group(['prefix' => '/home', 'middleware' => ['auth:sanctum']], function () { 
    Route::get('/', function() {
        return view('home', [
            'title' => 'Home Admin'
        ]);
    });
    Route::get('/filemanager', function() {
        return view('filemanager', [
            'title' => 'Filemanager Admin'
        ]);
    });
});
Route::get('/token', function (Request $request) { $token = $request->session()->token(); $token = csrf_token(); return $token; });

// route autentikasi
Route::group(['prefix' => '/akun'], function () {
    Route::post('/masuk', [UserController::class, 'login'])->name('user_masuk');
    Route::post('/daftar', [UserController::class, 'store'])->name('user_daftar');
    Route::post('/keluar', function () { Auth::logout(); });
    // cek status auth jika nuxt hot reload
    Route::get('/status', [UserController::class, 'status'])->name('user_status');
});

// route laravel file manager (lfm)
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth:sanctum']], function () {
     \UniSharp\LaravelFilemanager\Lfm::routes();
});

// proses email verifikasi
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/verify-success');
})->middleware(['auth', 'signed'])->name('verification.verify');


// form kirim ulang email verifikasi
Route::get('/email/verify', function () { return view('verify-email'); })->middleware('auth')->name('verification.notice');

// post kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json('Berhasil mengirim email verifikasi');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// halaman sukses kirim ulang email verifikasi
Route::get('/email/kirim-verifikasi', function () {
    $user = auth()->user();
    new Registered($user);
    return 'email terkirim';
});

// tampilan sukses verifikasi akun
Route::get('/verify-success', function () {
    return view('verify-success');
});

// post request lupa password link & token
Route::post('/forget-password', [UserController::class, 'forget_password'])->middleware('guest')->name('password.email');

// laman form reset password dengan token nya
Route::get('/reset-password/{token}', function ($token) {
    return view('reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// post password baru
Route::post('/reset-password', [UserController::class, 'reset_password'])->middleware('guest')->name('password.update');

// route landing web utama
Route::group(['prefix' => '/landing'], function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/maintenance', [LandingController::class, 'maintenance'])->name('maintenance');
});
