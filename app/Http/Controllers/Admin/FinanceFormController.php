<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FinanceForm;
use App\Models\FinanceNeed;
use Illuminate\Http\Request;

class FinanceFormController extends Controller
{
   
    public function index()
    {
        $needs = FinanceNeed::all();
        $forms = FinanceForm::with(['items.need', 'creator'])
            ->latest()
            ->get();

        return view('admin.finance_form.index', compact('needs', 'forms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'items' => 'required|array',
        ]);

        $form = FinanceForm::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'created_by' => auth()->id(),
        ]);

        $totalCost = 0;

        foreach ($data['items'] as $item) {

            $item['total_price'] = $item['quantity'] * $item['unit_price'];
            $totalCost += $item['total_price'];

            $form->items()->create([
                'finance_need_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        $form->update(['total_cost' => $totalCost]);

        return response()->json(['message' => 'تم حفظ النموذج بنجاح']);
    }

    public function show(FinanceForm $financeForm)
    {
        $financeForm->load(['items.need', 'creator']);
        return response()->json($financeForm);
    }

    public function destroy(FinanceForm $financeForm)
    {
        $financeForm->items()->delete();
        $financeForm->delete();

        return redirect()->route('admin.finance_form.index')
            ->with('success', 'تم حذف النموذج بنجاح');
    }

}
