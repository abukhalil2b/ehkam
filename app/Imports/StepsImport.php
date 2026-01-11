<?php

namespace App\Imports;

use App\Models\Step;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log; // Import Log
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StepsImport implements ToCollection, WithCalculatedFormulas
{
    protected $projectId;
    protected $activityId;
    protected $lastPhase = 'planning'; 

    public function __construct($projectId, $activityId)
    {
        $this->projectId = $projectId;
        $this->activityId = $activityId;
    }

    public function collection(Collection $rows)
    {
        Log::info('Start Importing Steps...'); // 1. Check if import starts
        
        $startProcessing = false;
        $orderCounter = 1;

        // Map Arabic Phase to DB Enum
        $phaseMap = [
            'التخطيط' => 'planning', // Partial match is safer
            'تخطيط' => 'planning',
            'التنفيذ' => 'implementation',
            'تنفيذ' => 'implementation',
            'المراجعة' => 'review',
            'مراجعة' => 'review',
            'الاعتماد' => 'close',
            'إغلاق' => 'close',
            'اغلاق' => 'close',
        ];

        $statusMap = [
            'لم يبدأ' => 'not_started',
            'قيد' => 'in_progress', // Matches "قيد التنفيذ"
            'متأخر' => 'delayed',
            'منجز' => 'completed',
            'معتمد' => 'approved',
        ];

        foreach ($rows as $index => $row) {
            // Convert row to string for easier searching (debugging)
            $rowString = json_encode($row->toArray(), JSON_UNESCAPED_UNICODE);

            // 1. Detect Header Row (LOOSE MATCHING)
            if (!$startProcessing) {
                // Check if any cell in this row contains "المرحلة" AND "خطوات"
                // We convert the whole row to a string to search ignoring column index
                if (Str::contains($rowString, 'المرحلة') && Str::contains($rowString, 'خطوات')) {
                    Log::info("Found Header at Row index: $index");
                    $startProcessing = true;
                }
                continue; 
            }

            // 2. Map Columns 
            // Based on your file: Col 2 = Phase, Col 3 = Name
            $phaseCell = isset($row[2]) ? trim($row[2]) : null;
            $name      = isset($row[3]) ? trim($row[3]) : null;

            // Debug: Log what we found for this row
            // Log::info("Processing Row $index: Name=[$name], Phase=[$phaseCell]");

            // Skip empty rows
            if (empty($name)) continue;

            // 3. Handle Merged Cells (Phase)
            if (!empty($phaseCell)) {
                foreach ($phaseMap as $key => $value) {
                    if (Str::contains($phaseCell, $key)) {
                        $this->lastPhase = $value;
                        break;
                    }
                }
            }

            // 4. Map Status (Loose Match)
            $statusCell = isset($row[9]) ? trim($row[9]) : '';
            $status = 'not_started';
            foreach ($statusMap as $key => $value) {
                if (Str::contains($statusCell, $key)) {
                    $status = $value;
                    break;
                }
            }

            // 5. Parse Dates
            $startDate = $this->transformDate($row[5] ?? null); 
            $endDate   = $this->transformDate($row[6] ?? null);

            // 6. Parse Percentage
            $rawPercent = floatval($row[8] ?? 0);
            $targetPercentage = $rawPercent <= 1 ? $rawPercent * 100 : $rawPercent;

            Log::info("Inserting Step: $name"); // Log success

            Step::create([
                'project_id'    => $this->projectId,
                'activity_id'   => $this->activityId,
                'name'          => $name,
                'phase'         => $this->lastPhase,
                'status'        => $status,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'target_percentage' => $targetPercentage,
                'supporting_document' => $row[4] ?? null,
                'ordered'       => $orderCounter++,
            ]);
        }
    }

    private function transformDate($value)
    {
        if (!$value) return null;
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $ex) {
                return null;
            }
        }
    }
}