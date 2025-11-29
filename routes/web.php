<?php

use App\Livewire\Admin\Student\Index as IndexStudent;
use App\Livewire\Admin\Teacher\Index as IndexTeacher;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('templates.app');
// });

Route::get('/', Login::class)->name('login');
Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
// Route::post('/login', 'livewire.auth.login')->name('login.auth');

Route::prefix('/admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'livewire.admin.dashboard')->name('dashboard');
    Route::prefix('/user')->name('user.')->group(function () {
        Route::prefix('/students')->name('student.')->group(function () {
            Route::get('/index', IndexStudent::class)->name('index');
        });
        Route::prefix('/teachers')->name('teacher.')->group(function () {
            Route::get('/index', Indexteacher::class)->name('index');
        });
    });
});

Route::prefix('/teacher')->name('teacher.')->group(function () {
    Route::view('/dashboard', 'livewire.teacher.dashboard')->name('dashboard');
});

Route::prefix('/student')->name('student.')->group(function () {
    Route::view('/dashboard', 'livewire.student.dashboard')->name('dashboard');
});
