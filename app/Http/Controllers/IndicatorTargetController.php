<?php

namespace App\Http\Controllers;

use App\Models\IndicatorTarget;
use App\Models\Indicator;
use Illuminate\Http\Request;

class IndicatorTargetController extends Controller
{
    public function edit(Indicator $indicator)
    {
        $targets = IndicatorTarget::where('indicator_id', $indicator->id)
            ->where('sector_id', null)
            ->orderBy('year')

            ->get();

        return view('indicator_target.edit', compact('targets', 'indicator'));
    }

    public function update(Request $request, Indicator $indicator)
    {
        $request->validate([
            'targets' => 'required|array',
            'targets.*.id' => 'required|exists:indicator_targets,id',
            'targets.*.target_value' => 'required|numeric|min:0',
        ]);

        foreach ($request->targets as $targetData) {
            IndicatorTarget::where('id', $targetData['id'])
                ->where('indicator_id', $indicator->id)
                ->update([
                    'target_value' => $targetData['target_value'],
                ]);
        }

        return redirect()
            ->route('indicator_target.edit', $indicator)
            ->with('success', 'تم تحديث جميع المستهدفات بنجاح');
    }
}
