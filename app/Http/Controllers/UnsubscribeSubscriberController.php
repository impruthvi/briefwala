<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Subscriber\UnsubscribeSubscriber;
use Illuminate\Http\RedirectResponse;

final class UnsubscribeSubscriberController extends Controller
{
    public function __construct(private readonly UnsubscribeSubscriber $unsubscribeSubscriber) {}

    public function __invoke(string $token): RedirectResponse
    {
        $this->unsubscribeSubscriber->handle($token);

        return to_route('unsubscribed');
    }
}
