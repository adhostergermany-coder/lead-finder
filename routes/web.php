<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
    Route::put('/leads/{lead}/quality', [LeadController::class, 'updateQuality'])->name('leads.updateQuality');
    Route::put('/leads/{lead}/contact', [LeadController::class, 'updateContact'])->name('leads.updateContact');
    Route::put('/leads/{lead}/note', [LeadController::class, 'updateNote'])->name('leads.updateNote');
    Route::put('/leads/{lead}/email', [LeadController::class, 'updateEmail'])->name('leads.updateEmail');
    Route::put('/leads/{lead}/whatsapp', [LeadController::class, 'updateWhatsapp'])->name('leads.updateWhatsapp');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
});
