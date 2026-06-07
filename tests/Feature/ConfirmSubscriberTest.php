<?php

declare(strict_types=1);

use App\Models\Subscriber;

test('valid confirm token confirms subscriber and redirects', function (): void {
    $subscriber = Subscriber::factory()->create();

    $response = $this->get(route('confirm', $subscriber->confirm_token));

    $response->assertRedirect(route('confirmed'));

    $subscriber->refresh();
    expect($subscriber->confirmed_at)->not->toBeNull();
    expect($subscriber->confirm_token)->toBeNull();
});

test('confirming nulls the confirm token', function (): void {
    $subscriber = Subscriber::factory()->create();
    $token = $subscriber->confirm_token;

    $this->get(route('confirm', $token));

    $subscriber->refresh();
    expect($subscriber->confirm_token)->toBeNull();
});

test('already confirmed token returns 404', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create();

    $response = $this->get(route('confirm', 'some-used-token'));

    $response->assertNotFound();
});

test('invalid confirm token returns 404', function (): void {
    $response = $this->get(route('confirm', 'non-existent-token'));

    $response->assertNotFound();
});

test('confirm token cannot be reused', function (): void {
    $subscriber = Subscriber::factory()->create();
    $token = $subscriber->confirm_token;

    $this->get(route('confirm', $token));

    $response = $this->get(route('confirm', $token));

    $response->assertNotFound();
});
