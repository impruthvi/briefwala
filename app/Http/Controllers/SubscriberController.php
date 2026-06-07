<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Subscriber\CreateSubscriber;
use App\Http\Requests\Subscriber\StoreSubscriberRequest;
use Illuminate\Http\RedirectResponse;

final class SubscriberController extends Controller
{
    public function __construct(private readonly CreateSubscriber $createSubscriber) {}

    public function store(StoreSubscriberRequest $request): RedirectResponse
    {
        $this->createSubscriber->handle($request->validated());

        return back();
    }
}
