<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\investigator\AccountController;
use App\Http\Controllers\investigator\AuditLogsController;
use App\Http\Controllers\investigator\DashboardController;
use App\Http\Controllers\investigator\DocumentationController;
use App\Http\Controllers\investigator\IncidentReportController;
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
Route::get('/track-case', [TrackCaseController::class, 'TrackCasePage'])->name('track.case.page');


// AUTH ROUTE
Route::get('/login', [AuthController::class, 'LoginPage'])->name('auth.login.page');

// INVESTIGATOR ROUTE
Route::get('/investigator/dashboard', [DashboardController::class, 'DashboardPage'])->name('investigator.dashboard.page');
Route::get('/investigator/accounts', [AccountController::class, 'AccountPage'])->name('investigator.account.page');
Route::get('/investigator/documentations', [DocumentationController::class, 'DocumentationPage'])->name('investigator.documentation.page');

// INCIDENTS ROUTE
Route::get('/investigator/incidents/reports', [IncidentReportController::class, 'IncidentReportPage'])->name('investigator.incident.report.page');
Route::get('/investigator/incidents/case', [IncidentReportController::class, 'IncidentCaseViewPage'])->name('investigator.incident.view.case.page');
Route::get('/investigator/incidents/print/case', [IncidentReportController::class, 'IncidentPrintCaseRequest'])->name('investigator.incident.print.case.page');

Route::get('/investigator/logs', [AuditLogsController::class, 'LogsPage'])->name('investigator.logs.page');
