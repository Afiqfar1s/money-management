<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentVoucherController extends Controller
{
    public function show(Payment $payment)
    {
        $payment->load('debtor');

        if ($payment->debtor->user_id !== auth()->id()) {
            abort(404);
        }

        return view('payments.voucher', compact('payment'));
    }
}
