<?php

namespace App\Http\Controllers;

use App\Models\BalanceAdjustment;
use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceAdjustmentController extends Controller
{
    public function store(Request $request, Debtor $debtor)
    {
        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'voucher_no' => 'nullable|string|max:255|unique:balance_adjustments,voucher_no',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'adjusted_at' => 'nullable|date',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

            BalanceAdjustment::create([
                'debtor_id' => $debtor->id,
                'voucher_no' => $validated['voucher_no'],
                'amount' => $validated['amount'],
                'note' => $validated['note'],
                'adjusted_at' => $validated['adjusted_at'] ?? now(),
            ]);

            // Increment starting outstanding
            $debtor->starting_outstanding += $validated['amount'];

            // Recompute outstanding
            $total_paid = $debtor->payments()->sum('amount');
            $debtor->outstanding = max(0, $debtor->starting_outstanding - $total_paid);
            $debtor->save();
        });

        return redirect()->back()->with('success', 'Balance adjustment recorded successfully.');
    }
}
