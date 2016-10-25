<?php
namespace BrandonFerens\Watchdog\Command;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Cache;

class Kennel extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watchdog:kennel {queues?* : An array of queues to clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear any watchdog cache";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Collect the queue names passed in as arguments
        $queues = collect($this->argument('queues') ?: config('watchdog.queues'))->unique();

        $queues->each(function ($queue) {
            Cache::forget("watchdog.{$queue}");
        });
    }
}
