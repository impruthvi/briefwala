<?php

declare(strict_types=1);

namespace App\Actions\Subscriber;

use App\Mail\ConfirmSubscription;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

final readonly class CreateSubscriber
{
    /** @param array<string, mixed> $data */
    public function handle(array $data): Subscriber
    {
        $subscriber = Subscriber::query()->create([
            'email' => $data['email'],
            'niche' => $data['niche'],
            'platform' => $data['platform'],
            'language' => $data['language'],
            'whatsapp_number' => $data['whatsapp_number'] ?? null,
            'referrer_id' => $data['referrer_id'] ?? Request::cookie('referrer_id'),
            'confirm_token' => Str::uuid(),
            'unsubscribe_token' => Str::uuid(),
        ]);

        Mail::to($subscriber->email)->send(new ConfirmSubscription($subscriber));

        return $subscriber;
    }
}
