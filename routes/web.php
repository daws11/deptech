<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Livewire\Auth\Login;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('leaves.index');
    }
    return view('auth.login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    // Logout Route
    Route::post('/logout', function () {
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');

    // Admin Management Routes
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/{admin}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
    });

    // Employee Management Routes
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // Leave Management Routes
    Route::prefix('leaves')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/create', [LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
        Route::get('/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
        Route::put('/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
        Route::delete('/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    });
});
