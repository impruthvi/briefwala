<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyResendWebhook
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.resend.webhook_secret');

        abort_if(empty($secret), 500, 'Resend webhook secret not configured.');

        $svixId = $request->header('svix-id');
        $svixTimestamp = $request->header('svix-timestamp');
        $svixSignature = $request->header('svix-signature');

        abort_if(! $svixId || ! $svixTimestamp || ! $svixSignature, 400, 'Missing webhook signature headers.');

        $toSign = sprintf('%s.%s.%s', $svixId, $svixTimestamp, $request->getContent());
        $secretBytes = base64_decode(str_replace('whsec_', '', $secret));
        $computed = 'v1,'.base64_encode(hash_hmac('sha256', $toSign, $secretBytes, true));

        $signatures = explode(' ', $svixSignature);

        foreach ($signatures as $sig) {
            if (hash_equals($computed, $sig)) {
                return $next($request);
            }
        }

        abort(403, 'Invalid webhook signature.');
    }
}
