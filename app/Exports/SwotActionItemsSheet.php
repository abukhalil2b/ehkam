<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SwotActionItemsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function collection()
    {
        $finalize = $this->project->finalize;

        if (!$finalize || !is_array($finalize->action_items)) {
            return new Collection();
        }

        return collect($finalize->action_items)->map(function ($item) {
            return [
                $item['title'] ?? '',
                $item['owner'] ?? '',
                $item['priority'] ?? '',
                $item['deadline'] ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Owner',
            'Priority',
            'Deadline',
        ];
    }

    public function title(): string
    {
        return 'Action Items';
    }
}
