<?php

namespace App\Console\Commands;

use App\Jobs\WorkcenterMigrationJob;
use Illuminate\Console\Command;

class WorkcenterMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infor:workcenter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It brings the work center data from Infor and loads it into the system\'s database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Process WorkcenterMigrationJob is running at ". now());
        WorkcenterMigrationJob::dispatch();
    }
}
