<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FinanceForm;
use App\Models\FinanceNeed;
use Illuminate\Http\Request;

class FinanceFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $needs = FinanceNeed::all();

        return view('admin.finance_form.index',compact('needs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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


    /**
     * Display the specified resource.
     */
    public function show(FinanceForm $financeForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceForm $financeForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceForm $financeForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceForm $financeForm)
    {
        //
    }
}
