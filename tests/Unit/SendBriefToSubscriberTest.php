<?php

declare(strict_types=1);

use App\Actions\Brief\SendBriefToSubscriber;
use App\Enums\SendStatus;
use App\Mail\WeeklyBrief;
use App\Models\SendLog;
use App\Models\Subscriber;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
});

test('creates send log with sent status on success', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();
    $weekOf = CarbonImmutable::parse('2026-06-01')->startOfWeek();

    (new SendBriefToSubscriber)->handle(
        subscriber: $subscriber,
        angles: [['hook' => 'Test hook', 'why' => 'Test why']],
        weekOf: $weekOf,
    );

    $this->assertDatabaseHas('send_logs', [
        'subscriber_id' => $subscriber->id,
        'status' => SendStatus::Sent->value,
    ]);
});

test('queues weekly brief mailable to subscriber', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();
    $weekOf = CarbonImmutable::parse('2026-06-01')->startOfWeek();

    (new SendBriefToSubscriber)->handle(
        subscriber: $subscriber,
        angles: [['hook' => 'Test hook', 'why' => 'Test why']],
        weekOf: $weekOf,
    );

    Mail::assertQueued(WeeklyBrief::class, fn (WeeklyBrief $mail): bool => $mail->hasTo($subscriber->email));
});

test('sets status to failed when mail send throws', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();
    $weekOf = CarbonImmutable::parse('2026-06-01')->startOfWeek();

    Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP failure'));

    (new SendBriefToSubscriber)->handle(
        subscriber: $subscriber,
        angles: [['hook' => 'Test hook', 'why' => 'Test why']],
        weekOf: $weekOf,
    );

    $this->assertDatabaseHas('send_logs', [
        'subscriber_id' => $subscriber->id,
        'status' => SendStatus::Failed->value,
    ]);
});

test('is idempotent when already sent this week', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();
    $weekOf = CarbonImmutable::parse('2026-06-01')->startOfWeek();

    // First send
    (new SendBriefToSubscriber)->handle(
        subscriber: $subscriber,
        angles: [['hook' => 'Hook', 'why' => 'Why']],
        weekOf: $weekOf,
    );

    // Second send — should be no-op
    (new SendBriefToSubscriber)->handle(
        subscriber: $subscriber,
        angles: [['hook' => 'Hook', 'why' => 'Why']],
        weekOf: $weekOf,
    );

    expect(
        SendLog::query()->where('subscriber_id', $subscriber->id)->count()
    )->toBe(1);
});
