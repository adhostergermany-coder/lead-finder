<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
