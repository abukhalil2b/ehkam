<?php

namespace App\Exports;

use App\Models\SwotBoard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SwotBoardSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function collection()
    {
        return SwotBoard::where('swot_project_id', $this->project->id)
            ->select('type', 'content', 'participant_name', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Type',
            'Content',
            'Participant',
            'Created At',
        ];
    }

    public function title(): string
    {
        return 'SWOT Board';
    }
}
