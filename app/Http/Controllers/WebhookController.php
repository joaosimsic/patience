<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Jobs\ProcessWebhookJob;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->validate([
            'id' => 'required|string',
            'type' => 'required|string',
            'data' => 'required|array',
        ]);

        $log = WebhookLog::firstOrCreate(
            ['external_id' => $payload['id']],
            [
                'type' => $payload['type'],
                'payload' => $payload,
                'status' => 'pending'
            ]
        );

        if (!$log->wasRecentlyCreated) {
            return response(['status' => 'Duplicate ignored'], 200);
        }

        ProcessWebhookJob::dispatch($log);

        return response(['status' => 'Webhook received'], 202);
    }
}
