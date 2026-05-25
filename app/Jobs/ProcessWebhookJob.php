<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use App\Events\WebhookProcessedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected WebhookLog $log) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payload = $this->log->payload;

        Log::info('Processing webhook data: ' . ($payload['id'] ?? $this->log->external_id));

        event(new WebhookProcessedEvent($this->log));

        $this->log->update(['status' => 'processed']);
    }
}
