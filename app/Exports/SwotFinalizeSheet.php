<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SwotFinalizeSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function collection()
    {
        $finalize = $this->project->finalize;

        if (!$finalize) {
            return new Collection();
        }

        return collect([
            [
                $finalize->summary,
                $finalize->strength_strategy,
                $finalize->weakness_strategy,
                $finalize->threat_strategy,
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Summary',
            'Strength Strategy',
            'Weakness Strategy',
            'Threat Strategy',
        ];
    }

    public function title(): string
    {
        return 'Finalize Summary';
    }
}
