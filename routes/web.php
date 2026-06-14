<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\DashboardController;

// === COMPANY ===
use App\Http\Controllers\Admin\Company\ProfileController as CompanyProfile;

// === EMPLOYEE ===
use App\Http\Controllers\Admin\Employee\EmployeeController as EmployeeProfile;

// === FINANCE ===
use App\Http\Controllers\Admin\Financial\FinancialController as Financial;

// === ROLE & AKSES ===
use App\Http\Controllers\Admin\RoleAkses\SetRoleController;

// === CHART ===
use App\Http\Controllers\Admin\ChartColorSetting\ChartController as ChartColor;

// === PROJECT ===
use App\Http\Controllers\Admin\ProjectEntry\ProjectController as ProjectEntry;
use App\Http\Controllers\Admin\ProjectUpdate\ProjectUpdateController as ProjectUpdate;
use App\Http\Controllers\Admin\ProjectFinish\ProjectFinishController as ProjectFinish;

// === PROJECT CATEGORY ===
use App\Http\Controllers\Admin\ProjectCategory\ProjectController as ProjectCategory;

// === REPORT ===
use App\Http\Controllers\Admin\Report\MonitoringController as ReportController;

// === NOTIFICATION ===
use App\Events\NewNotification;

// === CHAT LIVE ===
use App\Http\Controllers\Admin\ChatLive\RealtimeChat;
use App\Events\MessageSent;

Route::get('/notifications', function () {
    return DB::select('CALL SelectNotifications_xx26(?)', [10]);
});

Route::post('/notifications/read', function () {
    DB::statement('UPDATE notifications_xx26 SET is_read = 1 WHERE is_read = 0');
    return response()->json(['ok' => true]);
});

Route::post('/chat/send', [RealtimeChat::class, 'index'])->name('sendMessage');
Route::get('/getHistoryChat/{id}', [RealtimeChat::class, 'getHistoryChat'])->name('getHistoryChat');

Route::get('/', function () {
    return view('splash-screen');
})->name('splash');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


// =====================
// LOGIN & LOGOUT
// =====================
Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/auth', [LoginController::class, 'login'])->name('login.post');
    Route::get('/change-password', [LoginController::class, 'changePasswordView'])->name('login.change-password');
    Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('login.update-password');
    Route::post('/redirect', [LoginController::class, 'redirectToDashboard'])->name('login.redirect');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// =====================
// DASHBOARD
// =====================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// =====================
// COMPANY
// =====================
Route::get('/company-profile', [CompanyProfile::class, 'index'])->name('company-profile.index');
Route::prefix('company-profile')->group(function () {
    Route::post('/store', [CompanyProfile::class, 'store'])->name('company-profile.store');
});

// =====================
// EMPLOYEE
// =====================
Route::get('/employee-profile', [EmployeeProfile::class, 'index'])->name('employee-profile.index');
Route::prefix('employee-profile')->group(function () {
    Route::post('/store', [EmployeeProfile::class, 'store'])->name('employee-profile.store');
    Route::delete('/delete/{id}', [EmployeeProfile::class, 'delete'])->name('employee-profile.delete');
});

// =====================
// FINANCE
// =====================
Route::get('/financial', [Financial::class, 'index'])->name('financial.index');

Route::prefix('financial')->group(function () {
    Route::put('/{id}/budget', [Financial::class, 'updateBudget'])
        ->name('financial.updateBudget');
});

// =====================
// ROLE & AKSES
// =====================
Route::get('/set-role', [SetRoleController::class, 'index'])->name('set-role.index');
Route::prefix('set-role')->group(function () {
    Route::post('/store', [SetRoleController::class, 'store'])->name('set-role.store');
});

// =====================
// CHART COLOR SETTING
// =====================
Route::get('/chart-color-setting', [ChartColor::class, 'index'])->name('chart-color-setting.index');
Route::prefix('chart-color-setting')->group(function () {
    Route::post('/store', [ChartColor::class, 'store'])->name('chart-color-setting.store');
});

// =====================
// PROJECT CATEGORY
// =====================
Route::get('/project-category', [ProjectCategory::class, 'index'])->name('project-category.index');
Route::prefix('project-category')->group(function () {
    Route::post('/store', [ProjectCategory::class, 'store'])->name('project-category.store');
});

// =====================
// PROJECT ENTRY
// =====================
Route::get('/project-entry', [ProjectEntry::class, 'index'])->name('project-entry.index');
Route::prefix('project-entry')->group(function () {
    Route::get('/getHistory/{id}', [ProjectEntry::class, 'getHistory'])->name('project-entry.getHistory');
    Route::post('/store', [ProjectEntry::class, 'store'])->name('project-entry.store');
    Route::post('/update-progress', [ProjectEntry::class, 'updateProgress'])->name('project-entry.update-progress');
    Route::delete('/{id}', [ProjectEntry::class, 'destroy'])->name('project-entry.destroy');
});

// =====================
// PROJECT UPDATE
// =====================
Route::get('/project-update', [ProjectUpdate::class, 'index'])->name('project-update.index');
Route::prefix('project-update')->group(function () {
    Route::get('/chart-settings', [ProjectUpdate::class, 'chartSettings'])->name('project-update.chartSettings');
    Route::get('/getHistory/{id}', [ProjectUpdate::class, 'getHistory'])->name('project-update.getHistory');
    Route::post('/store', [ProjectUpdate::class, 'store'])->name('project-update.store');
    Route::post('/update-progress', [ProjectUpdate::class, 'updateProgress'])->name('project-update.update-progress');
    Route::get('/project-progress/{id}', [ProjectUpdate::class, 'projectProgress'])->name('project-update.project-progress');
    Route::post('/update-cost', [ProjectUpdate::class, 'updateCost'])->name('project-update.update-cost');
    Route::delete('/{id}', [ProjectUpdate::class, 'destroy'])->name('project-update.destroy');
});

// =====================
// PROJECT FINISH
// =====================
Route::get('/project-finish', [ProjectFinish::class, 'index'])->name('project-finish.index');
Route::prefix('project-finish')->group(function () {
    Route::get('/getHistory/{id}', [ProjectFinish::class, 'getHistory'])->name('project-finish.getHistory');
    Route::post('/store', [ProjectFinish::class, 'store'])->name('project-finish.store');
    Route::post('/update-progress', [ProjectFinish::class, 'updateProgress'])->name('project-finish.update-progress');
});

// =====================
// REPORT
// =====================
Route::get('/report', [ReportController::class, 'index'])->name('report.index');
Route::prefix('report')->name('report.')->group(function () {
    Route::get('/projects-by-category', [ReportController::class, 'getProjectsByCategory'])->name('projects-by-category');
    Route::get('/monitoring', [ReportController::class, 'index'])->name('monitoring');
});