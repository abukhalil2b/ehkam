<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SwotProjectFullExport implements WithMultipleSheets
{
    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function sheets(): array
    {
        return [
            new SwotBoardSheet($this->project),
            new SwotFinalizeSheet($this->project),
            new SwotBscStrategiesSheet($this->project),
            new SwotActionItemsSheet($this->project),
        ];
    }
}
