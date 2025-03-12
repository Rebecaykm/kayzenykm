<?php

namespace App\Console\Commands;

use App\Jobs\AddWorkCenterPartNumberJob;
use Illuminate\Console\Command;

class AddWorkCenterPartNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infor:work-part';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It associates the part numbers with the work centers.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AddWorkCenterPartNumberJob::dispatch();
    }
}
