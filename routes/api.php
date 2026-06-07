<?php

declare(strict_types=1);

use App\Http\Controllers\ResendWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/resend', ResendWebhookController::class)
    ->middleware('resend.webhook')
    ->name('webhooks.resend');
