<?php

namespace App\Console\Commands;

use App\Jobs\PartNumberMigrationJob;
use Illuminate\Console\Command;

class PartNumberMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infor:partnumber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It brings the part numbers from Infor and loads them into the system\'s database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PartNumberMigrationJob::dispatch();
    }
}
