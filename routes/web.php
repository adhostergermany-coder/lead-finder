<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::put('/leads/{lead}/quality', [LeadController::class, 'updateQuality'])->name('leads.updateQuality');
Route::put('/leads/{lead}/contact', [LeadController::class, 'updateContact'])->name('leads.updateContact');
Route::put('/leads/{lead}/email', [LeadController::class, 'updateEmail'])->name('leads.updateEmail');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
