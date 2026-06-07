<?php

declare(strict_types=1);

namespace App\Actions\Subscriber;

use App\Models\Subscriber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ConfirmSubscriber
{
    public function handle(string $token): Subscriber
    {
        $subscriber = Subscriber::where('confirm_token', $token)
            ->whereNull('confirmed_at')
            ->first();

        if (! $subscriber) {
            throw new NotFoundHttpException;
        }

        $subscriber->update([
            'confirmed_at' => now(),
            'confirm_token' => null,
        ]);

        return $subscriber;
    }
}
