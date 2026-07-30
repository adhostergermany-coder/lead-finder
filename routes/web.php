<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::put('/leads/{lead}/quality', [LeadController::class, 'updateQuality'])->name('leads.updateQuality');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
