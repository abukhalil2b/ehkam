<?php

namespace App\Exports;

use App\Models\SwotFinalizedStrategy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class SwotBscStrategiesSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function collection()
    {
        return $this->project->finalizedStrategies;
    }

    public function map($strategy): array
    {
        $labels = SwotFinalizedStrategy::getDimensionLabels();
        $initiatives = is_array($strategy->initiatives) ? implode("\n", $strategy->initiatives) : '';

        return [
            $labels[$strategy->dimension_type] ?? $strategy->dimension_type,
            $strategy->strategic_goal,
            $strategy->performance_indicator,
            $initiatives,
        ];
    }

    public function headings(): array
    {
        return [
            'Dimension',
            'Strategic Goal',
            'Performance Indicator',
            'Initiatives',
        ];
    }

    public function title(): string
    {
        return 'BSC Strategies';
    }
}
