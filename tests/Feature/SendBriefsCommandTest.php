<?php

declare(strict_types=1);

use App\Ai\Agents\BriefGenerator;
use App\Enums\SendStatus;
use App\Models\SendLog;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Http::fake();
    BriefGenerator::fake([['angles' => [
        ['hook' => 'Hook 1', 'why' => 'Why 1'],
        ['hook' => 'Hook 2', 'why' => 'Why 2'],
    ]]]);
});

test('command skips unconfirmed subscribers', function (): void {
    Subscriber::factory()->create(); // unconfirmed

    $this->artisan('brief:send')->assertSuccessful();

    Mail::assertNothingSent();
});

test('command skips unsubscribed subscribers', function (): void {
    Subscriber::factory()->confirmed()->unsubscribed()->create();

    $this->artisan('brief:send')->assertSuccessful();

    Mail::assertNothingSent();
});

test('command sends brief to confirmed subscriber', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create(['niche' => 'Tech', 'language' => 'Hindi']);

    $this->artisan('brief:send')->assertSuccessful();

    $this->assertDatabaseHas('send_logs', [
        'subscriber_id' => $subscriber->id,
        'status' => SendStatus::Sent->value,
    ]);
});

test('re-run does not send duplicate brief', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create(['niche' => 'Tech', 'language' => 'Hindi']);

    BriefGenerator::fake([
        ['angles' => [['hook' => 'Hook', 'why' => 'Why']]],
        ['angles' => [['hook' => 'Hook', 'why' => 'Why']]],
    ]);

    $this->artisan('brief:send');
    $this->artisan('brief:send');

    expect(
        SendLog::query()
            ->where('subscriber_id', $subscriber->id)
            ->where('status', SendStatus::Sent)
            ->count()
    )->toBe(1);
});

test('pre-send cleanup marks stuck sending rows as failed before resending', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create(['niche' => 'Tech', 'language' => 'Hindi']);

    $weekOf = Date::now('Asia/Kolkata')->startOfWeek()->toDateString();

    SendLog::query()->create([
        'subscriber_id' => $subscriber->id,
        'week_of' => $weekOf,
        'status' => SendStatus::Sending,
        'created_at' => now()->subHours(7),
        'updated_at' => now()->subHours(7),
    ]);

    $this->artisan('brief:send')->assertSuccessful();

    $this->assertDatabaseHas('send_logs', [
        'subscriber_id' => $subscriber->id,
        'status' => SendStatus::Sent->value,
    ]);
});

test('command outputs summary after completion', function (): void {
    Subscriber::factory()->confirmed()->create(['niche' => 'Tech', 'language' => 'Hindi']);

    $this->artisan('brief:send')
        ->expectsOutputToContain('brief:send complete')
        ->assertSuccessful();
});

test('two subscribers in same niche share one gpt call', function (): void {
    Subscriber::factory()->confirmed()->count(2)->create(['niche' => 'Tech', 'language' => 'Hindi']);

    BriefGenerator::fake([['angles' => [['hook' => 'Hook', 'why' => 'Why']]]]);

    $this->artisan('brief:send')->assertSuccessful();

    BriefGenerator::assertPrompted(fn ($prompt): bool => str_contains($prompt->prompt, 'Tech'));
});
