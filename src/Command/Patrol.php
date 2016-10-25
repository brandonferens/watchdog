<?php
namespace BrandonFerens\Watchdog\Command;

use BrandonFerens\Watchdog\Jobs\UpdateWatchdogStatus;
use BrandonFerens\Watchdog\Models\Watchdog;
use BrandonFerens\Watchdog\Notifications\Notify;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Patrol extends Command
{
    use DispatchesJobs;
    use Notifiable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watchdog:patrol {queues?* : An array of queues to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check queues are functioning normally";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Collect the queue names passed in as arguments
        $queues = collect($this->argument('queues') ?: config('watchdog.queues'))->unique();

        // Check any existing queued jobs to see if they have been updated
        $this->checkStatus($queues);

        // Queue new job(s)
        $this->queueNewJobs($queues);
    }

    /**
     * Check the status of each queue
     *
     * @param Collection $queues
     */
    private function checkStatus(Collection $queues)
    {
        $queues->each(function ($queue) {
            $status = Cache::pull("watchdog.{$queue}");

            d("Queue {$queue} status: {$status}");

            if ($status && $status != 'ok') {
                // Fire notifications
                (new Watchdog())->notify(new Notify($queue));
            }
        });
    }

    /**
     * Queue up new job for each queue
     *
     * @param Collection $queues
     */
    private function queueNewJobs(Collection $queues)
    {
        $queues->each(function ($queue) {
            d("Checking queue: {$queue}");
            // Create a record of any queues that need will be watched
            Cache::forever("watchdog.{$queue}", 'pending');

            // Fire job that checks if cache values have been updated
            $job = (new UpdateWatchdogStatus($queue))->onQueue($queue);
            $this->dispatch($job);
        });
    }
}
