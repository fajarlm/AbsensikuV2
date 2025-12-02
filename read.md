<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\admin\Dashboard;
use App\Livewire\admin\user\Index as UserIndex;
use App\Livewire\admin\student\Index as StudentIndex;
use App\Livewire\admin\Subject\Index as SubjectIndex;
use App\Livewire\admin\StudyGroup\Index as StudyGroupIndex;
use App\Livewire\admin\Schedule\Index as ScheduleIndex;
use App\Livewire\admin\attendance\Index as AttendanceIndex;
use App\Livewire\admin\teacher\Index as TeacherIndex;
use App\Livewire\Auth\Login;
use App\Livewire\teacher\Dashboard as TeacherDashboard;
use App\Livewire\Student\Dashboard as StudentDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'auth.login')->name('login');

Route::get('/', Login::class)->name('login');

// Route::view('/logout', 'livewire.auth.logout')->name('logout');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::get('/forgot-password',ForgotPassword::class)->name('forgot-password');

Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('/user')->name('user.')->group(function () {
        Route::get('/index', UserIndex::class)->name('index');

        Route::prefix('/students')->name('student.')->group(function () {
            Route::get('/index', StudentIndex::class)->name('index');
        });
        Route::prefix('/teachers')->name('teacher.')->group(function () {
            Route::get('/index', TeacherIndex::class)->name('index');
        });
    });

    Route::prefix('/subject')->name('subject.')->group(function () {
        Route::get('/index', SubjectIndex::class)->name('index');
    });

    Route::prefix('/study-group')->name('study_group.')->group(function () {
        Route::get('/index', StudyGroupIndex::class)->name('index');
        
    });

    Route::prefix('/schedule')->name('schedule.')->group(function () {
        Route::get('/index', ScheduleIndex::class)->name('index');

    });

    Route::prefix('/attendance')->name('attendance.')->group(function () {
        Route::get('/index', AttendanceIndex::class)->name('index');
    });
});

Route::prefix('/teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', TeacherDashboard::class)->name('dashboard');
});

Route::prefix('/student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
});
