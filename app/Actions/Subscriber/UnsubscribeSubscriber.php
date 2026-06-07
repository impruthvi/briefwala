<?php

declare(strict_types=1);

namespace App\Actions\Subscriber;

use App\Models\Subscriber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnsubscribeSubscriber
{
    public function handle(string $token): Subscriber
    {
        $subscriber = Subscriber::query()->where('unsubscribe_token', $token)
            ->whereNull('unsubscribed_at')
            ->first();

        throw_unless($subscriber, NotFoundHttpException::class);

        $subscriber->update([
            'unsubscribed_at' => now(),
        ]);

        return $subscriber;
    }
}
