<?php

use App\Http\Controllers\WorkReportController;
use App\Models\WorkReport;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::delete('/photo/{id}', [WorkReportController::class, 'deletePhoto'])->name('photo.delete');

Route::middleware('auth')->group(function () {
    Route::resource('workreport', WorkReportController::class);
});

Route::get(
    '/workreport/export/csv',
    [WorkReportController::class, 'exportCsv']
)
    ->name('workreport.export.csv');

Route::get(
    '/workreport/export/excel',
    [WorkReportController::class, 'exportExcel']
)
    ->name('workreport.export.excel');

Route::get(
    '/workreport/{id}/pdf',
    [WorkReportController::class, 'downloadPdf']
)
    ->middleware(['auth', 'role:Foreman,Supervisor,Dept. Head,Admin'])
    ->name('workreport.pdf');

Route::post('/workreport/{id}/status', [WorkReportController::class, 'updateStatus'])
    ->middleware(['auth', 'role:Foreman,Admin'])
    ->name('workreport.updateStatus');

Route::post('/workreport/{workReport}/foreman/approve', [WorkReportController::class, 'approveByForeman'])
    ->middleware(['auth', 'role:Foreman,Admin'])
    ->name('workreport.foreman.approve');

Route::post('/workreport/{workReport}/foreman/reject', [WorkReportController::class, 'rejectByForeman'])
    ->middleware(['auth', 'role:Foreman,Admin'])
    ->name('workreport.foreman.reject');

Route::post('/workreport/{workReport}/final/approve', [WorkReportController::class, 'approveFinal'])
    ->middleware(['auth', 'role:Supervisor,Dept. Head,Admin'])
    ->name('workreport.final.approve');

Route::post('/workreport/{workReport}/final/reject', [WorkReportController::class, 'rejectFinal'])
    ->middleware(['auth', 'role:Supervisor,Dept. Head,Admin'])
    ->name('workreport.final.reject');

Route::post(
    '/notifications/mark-all-read',
    [NotificationController::class, 'markAllRead']
)->name('notifications.markAllRead');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

Route::middleware('auth')->group(function () {

    Route::get(
        '/notifications/{notification}',
        [NotificationController::class, 'open']
    )->name('notifications.open');

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');
});
// route::get('/workreport', function () {
//     return view('workreport');
// });

// route::get('/cekdata', function () {
//     $workreport = WorkReport::all();
//     dd($workreport->toArray());
// });