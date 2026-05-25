<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Webhook-Signature');
        $secret = config('services.webhook.secret');

        if (!$signature) {
            return response(['error' => 'Signature missing'], 401);
        }

        $computedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($computedSignature, $signature)) {
            return response(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
