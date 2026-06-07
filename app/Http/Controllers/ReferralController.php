<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ReferralController extends Controller
{
    public function __invoke(Request $request, string $subscriberId): RedirectResponse
    {
        $exists = Subscriber::where('id', $subscriberId)
            ->whereNotNull('confirmed_at')
            ->exists();

        $response = redirect()->route('home');

        if ($exists) {
            $response->withCookie(
                cookie('referrer_id', $subscriberId, 60 * 24 * 30, secure: true, httpOnly: true)
            );
        }

        return $response;
    }
}
