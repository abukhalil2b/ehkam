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

        return view('admin.finance_form.index',compact('needs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'items' => 'required|array',
        ]);

        $form = FinanceForm::create([
            'title' => $data['title'],
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

        return response()->json(['message' => 'Saved']);
    }

}
