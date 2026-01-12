<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentVoucherController extends Controller
{
    public function show(Payment $payment)
    {
        $payment->load('debtor');

        $companyId = (int) session('current_company_id');
        if ((int) $payment->debtor->company_id !== $companyId) {
            abort(404);
        }

        return view('payments.voucher', compact('payment'));
    }
}
