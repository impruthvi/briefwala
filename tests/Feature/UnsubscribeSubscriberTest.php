<?php

declare(strict_types=1);

use App\Models\Subscriber;

test('valid unsubscribe token unsubscribes and redirects', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();

    $response = $this->get(route('unsubscribe', $subscriber->unsubscribe_token));

    $response->assertRedirect(route('unsubscribed'));

    $subscriber->refresh();
    expect($subscriber->unsubscribed_at)->not->toBeNull();
});

test('unsubscribe token is permanent and not nulled', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();
    $token = $subscriber->unsubscribe_token;

    $this->get(route('unsubscribe', $token));

    $subscriber->refresh();
    expect((string) $subscriber->unsubscribe_token)->toBe((string) $token);
});

test('already unsubscribed token returns 404', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->unsubscribed()->create();

    $response = $this->get(route('unsubscribe', $subscriber->unsubscribe_token));

    $response->assertNotFound();
});

test('invalid unsubscribe token returns 404', function (): void {
    $response = $this->get(route('unsubscribe', 'non-existent-token'));

    $response->assertNotFound();
});
