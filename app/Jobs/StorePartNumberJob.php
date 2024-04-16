<?php

namespace App\Jobs;

use App\Models\ItemClass;
use App\Models\Measurement;
use App\Models\PartNumber;
use App\Models\Planner;
use App\Models\Project;
use App\Models\StandardPackage;
use App\Models\Type;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StorePartNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $name;
    private $number;
    // private $openingBalance;
    private $measurementUnit;
    private $itemType;
    private $itemClass;
    private $standardPackage;
    private $boxQuantity;
    private $plannerCode;
    private $project;
    // private $createdDate;
    // private $createdTime;
    private $obsolete;

    /**
     * Create a new job instance.
     */
    public function __construct($name, $number, $measurementUnit, $itemType, $itemClass, $standardPackage, $boxQuantity, $plannerCode, $project, $obsolete)
    {
        $this->name = $name;
        $this->number = $number;
        $this->measurementUnit = $measurementUnit;
        $this->itemType = $itemType;
        $this->itemClass =  $itemClass;
        $this->standardPackage = $standardPackage;
        $this->boxQuantity = $boxQuantity;
        $this->plannerCode = $plannerCode;
        $this->project = $project;
        $this->obsolete = $obsolete;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $measurementType = Measurement::where('symbol', '=', $this->measurementUnit)->first();
        $type = Type::where('abbreviation', '=', $this->itemType)->first();
        $class = ItemClass::where('abbreviation', '=', $this->itemClass)->first();
        $package = StandardPackage::where('name', $this->standardPackage)->first();
        $planner = Planner::where('code', $this->plannerCode)->first();

        $item = PartNumber::where('number', $this->number)->first();

        if ($item !== null) {
            $item->update([
                'name' => $this->name,
                'measurement_id' => $measurementType->id ?? null,
                'type_id' => $type->id ?? null,
                'item_class_id' => $class->id ?? null,
                'standard_package_id' => $package->id ?? null,
                'quantity' => $this->boxQuantity ?? null,
                'planner_id' => $planner->id ?? null,
                'obsolete' => ($this->obsolete == "OBSOLETE") ? true : false,
            ]);
        } else {
            $item = PartNumber::create([
                'number' => $this->number,
                'name' => $this->name,
                'measurement_id' => $measurementType->id ?? null,
                'type_id' => $type->id ?? null,
                'item_class_id' => $class->id ?? null,
                'standard_package_id' => $package->id ?? null,
                'quantity' => $this->boxQuantity ?? null,
                'planner_id' => $planner->id ?? null,
                'obsolete' => ($this->obsolete == "OBSOLETE") ? true : false,
            ]);
        }

        switch (trim($this->project)) {
            case '1':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '2':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '3':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '4':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '5':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '7':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '8':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '9':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '10':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '11':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '3Y':
                $project = Project::where('type', '3Y')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '20':
                $project = Project::where('type', trim($this->project))->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '12':
                $project = Project::where('type', '1')->orWhere('type', '2')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '123':
                $project = Project::where('type', '1')->orWhere('type', '2')->orWhere('type', '3')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '13':
                $project = Project::where('type', '1')->orWhere('type', '3')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '23':
                $project = Project::where('type', '2')->orWhere('type', '3')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '45':
                $project = Project::where('type', '4')->orWhere('type', '5')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '56':
                $project = Project::where('type', '5')->orWhere('type', '6')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '710':
                $project = Project::where('type', '7')->orWhere('type', '10')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '79':
                $project = Project::where('type', '7')->orWhere('type', '9')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '47':
                $project = Project::where('type', '4')->orWhere('type', '7')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '57':
                $project = Project::where('type', '5')->orWhere('type', '7')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            case '811':
                $project = Project::where('type', '8')->orWhere('type', '11')->pluck('id')->toArray();
                $item->projects()->sync($project);
                break;

            default:
                Log::notice("StorePartNumberJob.- Sin Proyecto No. Parte : " . $this->number . ", Proyecto : " . $this->project);
                break;
        }
    }
}
