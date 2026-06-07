<?php

declare(strict_types=1);

namespace App\Actions\Subscriber;

use App\Models\Subscriber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnsubscribeSubscriber
{
    public function handle(string $token): Subscriber
    {
        $subscriber = Subscriber::where('unsubscribe_token', $token)
            ->whereNull('unsubscribed_at')
            ->first();

        if (! $subscriber) {
            throw new NotFoundHttpException;
        }

        $subscriber->update([
            'unsubscribed_at' => now(),
        ]);

        return $subscriber;
    }
}
