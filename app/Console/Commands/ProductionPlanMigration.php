<?php

namespace App\Console\Commands;

use App\Jobs\ProductionPlanMigrationJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProductionPlanMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infor:production-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It brings the daily production plan for the part numbers and loads it into the system\'s database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Process ProductionPlanMigration is running at ". now());
        ProductionPlanMigrationJob::dispatch();
    }
}
