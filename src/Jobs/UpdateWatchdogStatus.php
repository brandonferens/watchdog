<?php

namespace BrandonFerens\Watchdog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class UpdateWatchdogStatus implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $queueName;

    /**
     * Create a new job instance.
     *
     * @param string $queue
     */
    public function __construct(string $queue)
    {
        $this->queueName = $queue;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forever("watchdog.{$this->queueName}", 'ok');
    }
}
