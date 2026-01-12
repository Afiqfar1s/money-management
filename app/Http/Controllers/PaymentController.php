<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request, Debtor $debtor)
    {
        $companyId = (int) session('current_company_id');
        if ((int) $debtor->company_id !== $companyId) {
            abort(404);
        }

        $validated = $request->validate([
            'voucher_no' => 'required|string|max:255|unique:payments,voucher_no',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

            Payment::create([
                'debtor_id' => $debtor->id,
                'voucher_no' => $validated['voucher_no'],
                'amount' => $validated['amount'],
                'note' => $validated['note'],
                'paid_at' => $validated['paid_at'] ?? now(),
            ]);

            // Recompute outstanding
            $total_paid = $debtor->payments()->sum('amount');
            $debtor->outstanding = max(0, $debtor->starting_outstanding - $total_paid);
            $debtor->save();
        });

        return redirect()->route('debtors.show', $debtor)->with('success', 'Payment recorded successfully.');
    }
}
