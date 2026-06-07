<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ResendWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->json()->all();
        $type = $payload['type'] ?? null;
        $email = $payload['data']['email'] ?? null;

        if (! $email) {
            return response()->json(['ok' => true]);
        }

        match ($type) {
            'email.bounced' => $this->handleBounce($email, 'bounced'),
            'email.complained' => $this->handleBounce($email, 'complained'),
            default => null,
        };

        return response()->json(['ok' => true]);
    }

    private function handleBounce(string $email, string $reason): void
    {
        Subscriber::where('email', $email)
            ->whereNull('unsubscribed_at')
            ->update([
                'unsubscribed_at' => now(),
                'bounce_reason' => $reason,
            ]);
    }
}
