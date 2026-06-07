<?php

declare(strict_types=1);

use App\Http\Controllers\ConfirmSubscriberController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\UnsubscribeSubscriberController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'home')->name('home');

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->middleware('throttle:subscribe')
    ->name('subscribe');

Route::get('/confirm/{token}', ConfirmSubscriberController::class)->name('confirm');
Route::get('/unsubscribe/{token}', UnsubscribeSubscriberController::class)->name('unsubscribe');
Route::get('/ref/{subscriberId}', ReferralController::class)->name('referral');
Route::inertia('/confirmed', 'confirmed')->name('confirmed');
Route::inertia('/unsubscribed', 'unsubscribed')->name('unsubscribed');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
