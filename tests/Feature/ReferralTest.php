<?php

declare(strict_types=1);

use App\Models\Subscriber;

test('referral link redirects to home', function (): void {
    $referrer = Subscriber::factory()->confirmed()->create();

    $response = $this->get(route('referral', $referrer->id));

    $response->assertRedirect(route('home'));
});

test('referral link sets referrer_id cookie for confirmed subscriber', function (): void {
    $referrer = Subscriber::factory()->confirmed()->create();

    $response = $this->get(route('referral', $referrer->id));

    $response->assertCookie('referrer_id', (string) $referrer->id);
});

test('referral link does not set cookie for unconfirmed subscriber', function (): void {
    $referrer = Subscriber::factory()->create();

    $response = $this->get(route('referral', $referrer->id));

    $response->assertRedirect(route('home'));
    $response->assertCookieMissing('referrer_id');
});

test('referral link does not set cookie for non-existent subscriber', function (): void {
    $response = $this->get(route('referral', 'non-existent-id'));

    $response->assertRedirect(route('home'));
    $response->assertCookieMissing('referrer_id');
});

test('referrer_id cookie is stored on subscriber when subscribing', function (): void {
    $referrer = Subscriber::factory()->confirmed()->create();

    $this->withCookie('referrer_id', (string) $referrer->id)
        ->post(route('subscribe'), [
            'email' => 'new@example.com',
            'niche' => 'Tech',
            'platform' => 'YouTube',
            'language' => 'Hindi',
        ]);

    $this->assertDatabaseHas('subscribers', [
        'email' => 'new@example.com',
        'referrer_id' => (string) $referrer->id,
    ]);
});
