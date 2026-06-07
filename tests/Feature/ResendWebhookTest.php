<?php

declare(strict_types=1);

use App\Models\Subscriber;

function validWebhookHeaders(string $body, string $secret = 'whsec_dGVzdHNlY3JldA=='): array
{
    $svixId = 'msg_test_'.uniqid();
    $svixTimestamp = (string) time();
    $toSign = "{$svixId}.{$svixTimestamp}.{$body}";
    $secretBytes = base64_decode(str_replace('whsec_', '', $secret));
    $signature = 'v1,'.base64_encode(hash_hmac('sha256', $toSign, $secretBytes, true));

    return [
        'svix-id' => $svixId,
        'svix-timestamp' => $svixTimestamp,
        'svix-signature' => $signature,
    ];
}

beforeEach(function (): void {
    config(['services.resend.webhook_secret' => 'whsec_dGVzdHNlY3JldA==']);
});

test('bounce event marks subscriber as unsubscribed', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create(['email' => 'bounce@example.com']);

    $body = json_encode(['type' => 'email.bounced', 'data' => ['email' => 'bounce@example.com']]);

    $this->postJson(route('webhooks.resend'), json_decode($body, true), validWebhookHeaders($body))
        ->assertOk();

    $subscriber->refresh();
    expect($subscriber->unsubscribed_at)->not->toBeNull();
    expect($subscriber->bounce_reason)->toBe('bounced');
});

test('complaint event marks subscriber as unsubscribed', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create(['email' => 'complaint@example.com']);

    $body = json_encode(['type' => 'email.complained', 'data' => ['email' => 'complaint@example.com']]);

    $this->postJson(route('webhooks.resend'), json_decode($body, true), validWebhookHeaders($body))
        ->assertOk();

    $subscriber->refresh();
    expect($subscriber->unsubscribed_at)->not->toBeNull();
    expect($subscriber->bounce_reason)->toBe('complained');
});

test('unknown event type returns ok without side effects', function (): void {
    $subscriber = Subscriber::factory()->confirmed()->create(['email' => 'safe@example.com']);

    $body = json_encode(['type' => 'email.opened', 'data' => ['email' => 'safe@example.com']]);

    $this->postJson(route('webhooks.resend'), json_decode($body, true), validWebhookHeaders($body))
        ->assertOk();

    $subscriber->refresh();
    expect($subscriber->unsubscribed_at)->toBeNull();
});

test('request without svix headers returns 400', function (): void {
    $this->postJson(route('webhooks.resend'), ['type' => 'email.bounced', 'data' => ['email' => 'x@x.com']])
        ->assertStatus(400);
});

test('request with invalid signature returns 403', function (): void {
    $body = json_encode(['type' => 'email.bounced', 'data' => ['email' => 'x@x.com']]);

    $this->postJson(route('webhooks.resend'), json_decode($body, true), [
        'svix-id' => 'msg_fake',
        'svix-timestamp' => (string) time(),
        'svix-signature' => 'v1,invalidsignature',
    ])->assertStatus(403);
});
