<?php

declare(strict_types=1);

namespace App\Actions\Subscriber;

use App\Mail\ConfirmSubscription;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final readonly class CreateSubscriber
{
    public function handle(array $data): Subscriber
    {
        $subscriber = Subscriber::create([
            'email' => $data['email'],
            'niche' => $data['niche'],
            'platform' => $data['platform'],
            'language' => $data['language'],
            'whatsapp_number' => $data['whatsapp_number'] ?? null,
            'referrer_id' => $data['referrer_id'] ?? null,
            'confirm_token' => Str::uuid(),
            'unsubscribe_token' => Str::uuid(),
        ]);

        Mail::to($subscriber->email)->send(new ConfirmSubscription($subscriber));

        return $subscriber;
    }
}
