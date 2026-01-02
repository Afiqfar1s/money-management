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
        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

            // Generate unique voucher number
            $voucher_no = $this->generateUniqueVoucherNo();

            Payment::create([
                'debtor_id' => $debtor->id,
                'voucher_no' => $voucher_no,
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

    private function generateUniqueVoucherNo(): string
    {
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $date = now()->format('Ymd');
            $time = now()->format('His');
            $random = strtoupper(Str::random(4));
            $voucher_no = "VCH-{$date}-{$time}-{$random}";

            $exists = Payment::where('voucher_no', $voucher_no)->exists();
            $attempt++;

            if (!$exists) {
                return $voucher_no;
            }

            usleep(100000); // 100ms delay before retry
        } while ($attempt < $maxAttempts);

        throw new \Exception('Failed to generate unique voucher number');
    }
}
