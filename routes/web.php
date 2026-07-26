<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\SlotMapController;
use App\Http\Controllers\ParkingHistoryController;
use App\Http\Controllers\FeeRateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('vehicles', VehicleController::class)->parameters(['vehicles' => 'vehicle:vehicle_id']);

    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::post('/checkin/slots', [CheckInController::class, 'slotSelection'])->name('checkin.slots');
    Route::post('/checkin/assign', [CheckInController::class, 'assignSlot'])->name('checkin.assign');
    Route::get('/checkin/ticket/{ticketId}', [CheckInController::class, 'ticket'])->name('checkin.ticket');

    Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/payment/{ticketId}', [CheckOutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{ticketId}', [CheckOutController::class, 'processPayment'])->name('checkout.process');
    Route::get('/checkout/complete/{ticketId}', [CheckOutController::class, 'complete'])->name('checkout.complete');

    Route::get('/slots', [SlotMapController::class, 'index'])->name('slots.index');
    Route::get('/slots/{slot:slot_id}', [SlotMapController::class, 'show'])->name('slots.show');
    Route::patch('/slots/{slot:slot_id}/status', [SlotMapController::class, 'updateStatus'])->name('slots.updateStatus');

    Route::get('/history', [ParkingHistoryController::class, 'index'])->name('history.index');
    Route::get('/history/vehicle/{plate}', [ParkingHistoryController::class, 'vehicleHistory'])->name('history.vehicle');

    Route::get('/fees', [FeeRateController::class, 'index'])->name('fees.index');
    Route::patch('/fees/{rate:rate_id}', [FeeRateController::class, 'update'])->name('fees.update');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::get('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::get('/settings/system', [SettingsController::class, 'systemSettings'])->name('settings.system');
    Route::post('/settings/system', [SettingsController::class, 'updateSystemSettings'])->name('settings.updateSystem');

    Route::middleware('admin')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::resource('staff', StaffController::class)->parameters(['staff' => 'staff:staff_id']);
        Route::patch('/staff/{staff:staff_id}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggleStatus');
    });
});
