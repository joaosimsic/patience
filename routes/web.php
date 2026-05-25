<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyWebhookSignature;

Route::post('/webhooks/stripe', [WebhookController::class, 'handle'])
    ->middleware(VerifyWebhookSignature::class);
