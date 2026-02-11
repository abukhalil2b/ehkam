<?php

namespace App\Exports;

use App\Models\WorkshopAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkshopAttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $workshopId;

    public function __construct($workshopId)
    {
        $this->workshopId = $workshopId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return WorkshopAttendance::where('workshop_id', $this->workshopId)->get();
    }

    public function map($attendance): array
    {
        return [
            $attendance->attendee_name,
            $attendance->job_title,
            $attendance->department,
            $attendance->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Participant Name',
            'Job Title',
            'Department',
            'Registration Date/Time',
        ];
    }
}
