<?php

declare(strict_types=1);

use App\Mail\ConfirmSubscription;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
});

// --- Happy path ---

test('guest can subscribe with valid data', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('subscribers', [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);
});

test('confirmation email is sent after subscription', function (): void {
    $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    Mail::assertQueued(ConfirmSubscription::class, fn (ConfirmSubscription $mail): bool => $mail->hasTo('creator@example.com'));
});

test('subscriber is created with confirm_token and unsubscribe_token', function (): void {
    $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $subscriber = Subscriber::query()->where('email', 'creator@example.com')->first();

    expect($subscriber->confirm_token)->not->toBeNull();
    expect($subscriber->unsubscribe_token)->not->toBeNull();
    expect($subscriber->confirmed_at)->toBeNull();
});

test('subscriber can include optional whatsapp number', function (): void {
    $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
        'whatsapp_number' => '+919876543210',
    ]);

    $this->assertDatabaseHas('subscribers', [
        'email' => 'creator@example.com',
        'whatsapp_number' => '+919876543210',
    ]);
});

// --- Validation: email ---

test('email is required', function (): void {
    $response = $this->post(route('subscribe'), [
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('email');
});

test('email must be valid', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'not-an-email',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('email');
});

test('duplicate email is rejected', function (): void {
    Subscriber::factory()->create(['email' => 'taken@example.com']);

    $response = $this->post(route('subscribe'), [
        'email' => 'taken@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('email');
});

// --- Validation: niche ---

test('niche is required', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('niche');
});

test('niche must be from allowed list', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Crypto',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('niche');
});

// --- Validation: platform ---

test('platform is required', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('platform');
});

test('platform must be from allowed list', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'TikTok',
        'language' => 'Hindi',
    ]);

    $response->assertSessionHasErrors('platform');
});

// --- Validation: language ---

test('language is required', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
    ]);

    $response->assertSessionHasErrors('language');
});

test('language must be from allowed list', function (): void {
    $response = $this->post(route('subscribe'), [
        'email' => 'creator@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'French',
    ]);

    $response->assertSessionHasErrors('language');
});

// --- Rate limiting ---

test('subscribe endpoint is rate limited', function (): void {
    foreach (range(1, 5) as $i) {
        $this->post(route('subscribe'), [
            'email' => sprintf('creator%s@example.com', $i),
            'niche' => 'Tech',
            'platform' => 'YouTube',
            'language' => 'Hindi',
        ]);
    }

    $response = $this->post(route('subscribe'), [
        'email' => 'creator6@example.com',
        'niche' => 'Tech',
        'platform' => 'YouTube',
        'language' => 'Hindi',
    ]);

    $response->assertStatus(429);
});
