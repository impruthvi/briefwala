<?php

declare(strict_types=1);

namespace App\Actions\Subscriber;

use App\Models\Subscriber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ConfirmSubscriber
{
    public function handle(string $token): Subscriber
    {
        $subscriber = Subscriber::query()->where('confirm_token', $token)
            ->whereNull('confirmed_at')
            ->first();

        throw_unless($subscriber, NotFoundHttpException::class);

        $subscriber->update([
            'confirmed_at' => now(),
            'confirm_token' => null,
        ]);

        return $subscriber;
    }
}
