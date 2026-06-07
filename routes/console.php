<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sunday 22:00 IST = Sunday 16:30 UTC — brief lands in inbox Monday morning IST
Schedule::command('brief:send')
    ->weeklyOn(0, '22:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping()
    ->onOneServer();
