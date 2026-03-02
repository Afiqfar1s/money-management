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
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->hasPermission('manage_payments')) {
            abort(403);
        }

        $companyId = (int) session('current_company_id');
        if ((int) $debtor->company_id !== $companyId) {
            abort(404);
        }

        $validated = $request->validate([
            'voucher_no' => 'required|string|max:255|unique:payments,voucher_no',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ], [
            // Custom validation messages
            'voucher_no.required' => 'Please enter a voucher number for this payment.',
            'voucher_no.unique' => 'This voucher number has already been used. Please use a unique voucher number.',
            'voucher_no.max' => 'Voucher number cannot exceed 255 characters.',
            'amount.required' => 'Please enter the payment amount.',
            'amount.numeric' => 'Payment amount must be a valid number.',
            'amount.min' => 'Payment amount must be at least 0.01.',
            'paid_at.date' => 'Please enter a valid payment date.',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

            Payment::create([
                'debtor_id' => $debtor->id,
                'voucher_no' => $validated['voucher_no'],
                'amount' => $validated['amount'],
                'note' => $validated['note'] ?? null,
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
