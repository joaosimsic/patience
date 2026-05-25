<?php

use App\Http\Controllers\WebhookController;
use App\Http\Middleware\VerifyWebhookSignature;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/stripe', [WebhookController::class, 'handle']);
    /* ->middleware(VerifyWebhookSignature::class); */
