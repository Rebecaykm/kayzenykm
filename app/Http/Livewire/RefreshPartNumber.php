<?php

namespace App\Http\Livewire;

use App\Jobs\AddWorkCenterPartNumberJob;
use App\Jobs\PartNumberMigrationJob;
use App\Jobs\WorkcenterMigrationJob;
use Livewire\Component;

class RefreshPartNumber extends Component
{
    /**
     *
     */
    public function refreshPartNumber()
    {
        WorkcenterMigrationJob::dispatch();

        PartNumberMigrationJob::dispatch();

        AddWorkCenterPartNumberJob::dispatch();
    }

    /**
     *
     */
    public function render()
    {
        return view('livewire.refresh-part-number');
    }
}
