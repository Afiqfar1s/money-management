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
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->hasPermission('manage_adjustments')) {
            abort(403);
        }

    $companyId = (int) session('current_company_id');
    if ((int) $debtor->company_id !== $companyId) {
            abort(404);
        }

        $validated = $request->validate([
            'voucher_no' => 'nullable|string|max:255|unique:balance_adjustments,voucher_no',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'adjusted_at' => 'nullable|date',
        ], [
            // Custom validation messages
            'voucher_no.unique' => 'This voucher number has already been used. Please use a unique voucher number.',
            'voucher_no.max' => 'Voucher number cannot exceed 255 characters.',
            'amount.required' => 'Please enter the adjustment amount.',
            'amount.numeric' => 'Adjustment amount must be a valid number.',
            'amount.min' => 'Adjustment amount must be at least 0.01.',
            'adjusted_at.date' => 'Please enter a valid adjustment date.',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

            BalanceAdjustment::create([
                'debtor_id' => $debtor->id,
                'voucher_no' => $validated['voucher_no'] ?? null,
                'amount' => $validated['amount'],
                'note' => $validated['note'] ?? null,
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
