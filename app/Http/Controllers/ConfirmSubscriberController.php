<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Subscriber\ConfirmSubscriber;
use Illuminate\Http\RedirectResponse;

final class ConfirmSubscriberController extends Controller
{
    public function __construct(private readonly ConfirmSubscriber $confirmSubscriber) {}

    public function __invoke(string $token): RedirectResponse
    {
        $this->confirmSubscriber->handle($token);

        return redirect()->route('confirmed');
    }
}
