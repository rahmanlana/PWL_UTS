<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/struk/{id}', [StrukController::class, 'print'])->name('struk.print');
});
