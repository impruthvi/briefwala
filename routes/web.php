<?php

declare(strict_types=1);

use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'home')->name('home');

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->middleware('throttle:subscribe')
    ->name('subscribe');
Route::inertia('/confirmed', 'confirmed')->name('confirmed');
Route::inertia('/unsubscribed', 'unsubscribed')->name('unsubscribed');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
