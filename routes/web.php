<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'auth.login')->name('login');
Route::view('/', 'auth.login')->name('login');
// Route::view('/logout', 'livewire.auth.logout')->name('logout');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::view('/forgot-password','auth.forgot-password')->name('forgot-password');

Route::prefix('/admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::prefix('/user')->name('user.')->group(function () {
        Route::view('/index', 'admin.user.index')->name('index');
        Route::view('/trash', 'admin.user.trash')->name('trash');

        Route::prefix('/students')->name('student.')->group(function () {
            Route::view('/index', 'admin.student.index')->name('index');
            Route::view('/trash', 'admin.student.trash')->name('trash');
        });
        Route::prefix('/teachers')->name('teacher.')->group(function () {
            Route::view('/index', 'admin.teacher.index')->name('index');
            Route::view('/trash', 'admin.teacher.trash')->name('trash');
        });
    });

    Route::prefix('/subject')->name('subject.')->group(function () {
        Route::view('/index', 'admin.subject.index')->name('index');
        Route::view('/trash', 'admin.subject.trash')->name('trash');
    });

    Route::prefix('/study-group')->name('study_group.')->group(function () {
        Route::view('/index', 'admin.study-group.index')->name('index');
        Route::view('/trash', 'admin.study-group.trash')->name('trash');
        
    });

    Route::prefix('/schedule')->name('schedule.')->group(function () {
        Route::view('/index', 'admin.schedule.index')->name('index');
        Route::view('/trash', 'admin.schedule.trash')->name('trash');

    });

    Route::prefix('/attendance')->name('attendance.')->group(function () {
        Route::view('/index', 'admin.attendance.index')->name('index');
        Route::view('/trash', 'admin.attendance.trash')->name('trash');

    });
});

Route::prefix('/teacher')->name('teacher.')->group(function () {
    Route::view('/dashboard', 'teacher.dashboard')->name('dashboard');
});

Route::prefix('/student')->name('student.')->group(function () {
    Route::view('/dashboard', 'student.dashboard')->name('dashboard');
});
