<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\investigator\AccountController;
use App\Http\Controllers\investigator\AuditLogsController;
use App\Http\Controllers\investigator\DashboardController;
use App\Http\Controllers\investigator\DocumentationController;
use App\Http\Controllers\investigator\IncidentReportController;
use App\Http\Controllers\investigator\NotificationController;
use App\Http\Controllers\investigator\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrackCaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'HomePage'])->name('home.page');
Route::get('/report', [ReportController::class, 'ReportPage'])->name('report.page');
Route::post('/submit-report', [ReportController::class, 'CreateReportPage'])->name('report.submit');
Route::get('/report/verify', [ReportController::class, 'VerifyOtpPage'])->name('report.verify.page');
Route::post('/report/verify', [ReportController::class, 'VerifyOtpRequest'])->name('report.verify.submit');
Route::get('/track-case', [TrackCaseController::class, 'TrackCasePage'])->name('track.case.page');


// AUTH ROUTE
Route::get('/login', [AuthController::class, 'LoginPage'])->name('auth.login.page');
Route::post('/login', [AuthController::class, 'LoginRequest'])->name('auth.login.submit');
Route::post('/logout', [AuthController::class, 'LogoutRequest'])->name('auth.logout');

// INVESTIGATOR ROUTE
Route::middleware('investigator.auth')->group(function () {

    Route::get('/investigator/dashboard', [DashboardController::class, 'DashboardPage'])->name('investigator.dashboard.page');
    Route::get('/investigator/dashboard/data', [DashboardController::class, 'DashboardData'])->name('investigator.dashboard.data');

    // INVESTIGATOR PROFILE ROUTE
    Route::get('/investigator/profile', [ProfileController::class, 'ProfilePage'])->name('investigator.profile.page');
    Route::put('/investigator/profile/email', [ProfileController::class, 'UpdateEmailRequest'])->name('investigator.profile.email.update');
    Route::put('/investigator/profile/password', [ProfileController::class, 'UpdatePasswordRequest'])->name('investigator.profile.password.update');

    // INVESTIGATOR NOTIFICATION ROUTE
    Route::get('/investigator/notifications', [NotificationController::class, 'NotificationPage'])->name('investigator.notification.page');
    Route::get('/investigator/notifications/realtime', [NotificationController::class, 'RealtimeDataRequest'])->name('investigator.notification.realtime');
    Route::patch('/investigator/notifications/read-all', [NotificationController::class, 'MarkAllAsReadRequest'])->name('investigator.notification.read.all');
    Route::patch('/investigator/notifications/{notification}/read', [NotificationController::class, 'MarkAsReadRequest'])->name('investigator.notification.read');

    // INVESTIGATOR ACCOUNT ROUTE

    Route::put('/investigator/accounts/{investigator}/update', [AccountController::class, 'UpdateAccountRequest'])->name('investigator.account.update');
    Route::delete('/investigator/accounts/{investigator}/delete', [AccountController::class, 'DeleteAccountRequest'])->name('investigator.account.delete');

    // INVESTIGATOR DOCUMENTATION ROUTE
    Route::get('/investigator/documentations', [DocumentationController::class, 'DocumentationPage'])->name('investigator.documentation.page');
    Route::get('/investigator/documentations/{year}/{month}/reports', [DocumentationController::class, 'DocumentationReportsPage'])
        ->whereNumber('year')
        ->whereNumber('month')
        ->name('investigator.documentation.reports.page');
    Route::get('/investigator/documentations/reports/{incident}/print', [DocumentationController::class, 'DocumentationPrintReportPage'])
        ->whereNumber('incident')
        ->name('investigator.documentation.print.report.page');

    // INVESTIGATOR INCIDENTS ROUTE
    Route::get('/investigator/incidents/reports', [IncidentReportController::class, 'IncidentReportPage'])->name('investigator.incident.report.page');
    Route::get('/investigator/incidents/reports/data', [IncidentReportController::class, 'IncidentReportDataRequest'])->name('investigator.incident.report.data');
    Route::post('/investigator/incidents/reports/create', [IncidentReportController::class, 'CreateIncidentRequest'])->name('investigator.incident.report.create');
    Route::patch('/investigator/incidents/{incident}/status', [IncidentReportController::class, 'UpdateIncidentStatusRequest'])
        ->whereNumber('incident')
        ->name('investigator.incident.status.update');
    Route::patch('/investigator/incidents/{incident}/details', [IncidentReportController::class, 'UpdateIncidentDetailsRequest'])
        ->whereNumber('incident')
        ->name('investigator.incident.details.update');
    Route::get('/investigator/incidents/{incident}/case', [IncidentReportController::class, 'IncidentCaseViewPage'])
        ->whereNumber('incident')
        ->name('investigator.incident.view.case.page');
    Route::get('/investigator/incidents/print/case', [IncidentReportController::class, 'IncidentPrintCaseRequest'])->name('investigator.incident.print.case.page');

    // INVESTIGATOR AUDIT LOGS ROUTE
    Route::get('/investigator/logs', [AuditLogsController::class, 'LogsPage'])->name('investigator.logs.page');
});
    Route::get('/investigator/accounts', [AccountController::class, 'AccountPage'])->name('investigator.account.page');
    Route::post('/investigator/accounts/create', [AccountController::class, 'CreateAccountRequest'])->name('investigator.account.create');