<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\Payment;
use App\Models\BalanceAdjustment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Generate PDF for individual debtor payment history
     */
    public function debtorPaymentHistory($debtorId)
    {
        $debtor = Debtor::with(['payments', 'balanceAdjustments'])->findOrFail($debtorId);

        $companyId = (int) session('current_company_id');
        if ((int) $debtor->company_id !== $companyId) {
            abort(404);
        }

        // Get all transactions (payments and adjustments) combined and sorted
        $transactions = collect();
        
        // Add payments
        foreach ($debtor->payments as $payment) {
            $transactions->push([
                'date' => $payment->paid_at,
                'type' => 'Payment',
                'description' => $payment->note ?? 'Payment received',
                'amount' => $payment->amount,
                'voucher_no' => $payment->voucher_no ?? '-',
                'created_at' => $payment->created_at,
            ]);
        }
        
        // Add balance adjustments
        foreach ($debtor->balanceAdjustments as $adjustment) {
            $transactions->push([
                'date' => $adjustment->adjusted_at,
                'type' => $adjustment->amount > 0 ? 'Balance Addition' : 'Balance Deduction',
                'description' => $adjustment->note,
                'amount' => $adjustment->amount,
                'voucher_no' => $adjustment->voucher_no ?? '-',
                'created_at' => $adjustment->created_at,
            ]);
        }
        
        // Sort by date descending
        $transactions = $transactions->sortByDesc('date')->values();
        
        $data = [
            'debtor' => $debtor,
            'transactions' => $transactions,
            'totalPaid' => $debtor->payments->sum('amount'),
            'generatedDate' => now()->format('d M Y, h:i A'),
            'generatedBy' => Auth::user()->name,
        ];
        
        $pdf = Pdf::loadView('reports.debtor-payment-history', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'Payment_History_' . str_replace(' ', '_', $debtor->name) . '_' . date('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Show all transactions page (admin only)
     */
    public function allTransactionsPage(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access - Admin only');
        }

        $companyId = (int) session('current_company_id');
        if ($companyId <= 0) {
            return redirect()->route('companies.select');
        }

        // Build payment query with filters
        $paymentsQuery = DB::table('payments')
            ->join('debtors', 'payments.debtor_id', '=', 'debtors.id')
            ->select(
                'payments.id',
                'payments.paid_at as date',
                'payments.amount',
                'payments.note as description',
                'payments.voucher_no',
                'payments.created_at',
                'debtors.name as debtor_name',
        DB::raw("'Payment' as type")
            );

    $paymentsQuery->where('debtors.company_id', $companyId);

        // Apply filters to payments
        if ($request->filled('debtor')) {
            $paymentsQuery->where('debtors.name', 'like', '%' . $request->debtor . '%');
        }

        if ($request->filled('from_date')) {
            $paymentsQuery->where('payments.paid_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $paymentsQuery->where('payments.paid_at', '<=', $request->to_date);
        }

        // Build adjustments query with filters
        $adjustmentsQuery = DB::table('balance_adjustments')
            ->join('debtors', 'balance_adjustments.debtor_id', '=', 'debtors.id')
            ->select(
                'balance_adjustments.id',
                'balance_adjustments.adjusted_at as date',
                'balance_adjustments.amount',
                'balance_adjustments.note as description',
                DB::raw("NULL as voucher_no"),
                'balance_adjustments.created_at',
                'debtors.name as debtor_name',
                DB::raw("CASE WHEN balance_adjustments.amount > 0 THEN 'Balance Addition' ELSE 'Balance Deduction' END as type")
            );

    $adjustmentsQuery->where('debtors.company_id', $companyId);

        // Apply filters to adjustments
        if ($request->filled('debtor')) {
            $adjustmentsQuery->where('debtors.name', 'like', '%' . $request->debtor . '%');
        }

        if ($request->filled('from_date')) {
            $adjustmentsQuery->where('balance_adjustments.adjusted_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $adjustmentsQuery->where('balance_adjustments.adjusted_at', '<=', $request->to_date);
        }

        // Union and paginate
        $transactions = $paymentsQuery
            ->union($adjustmentsQuery)
            ->orderBy('date', 'desc')
            ->paginate(50);

        return view('reports.all-transactions', compact('transactions'));
    }

    /**
     * Generate PDF for all transactions (admin only)
     */
    public function downloadAllTransactions(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access - Admin only');
        }

        $companyId = (int) session('current_company_id');
        if ($companyId <= 0) {
            return redirect()->route('companies.select');
        }

        // Build payment query with filters
        $paymentsQuery = DB::table('payments')
            ->join('debtors', 'payments.debtor_id', '=', 'debtors.id')
            ->select(
                'payments.paid_at as date',
                'payments.amount',
                'payments.note as description',
                'payments.voucher_no',
                'debtors.name as debtor_name',
                DB::raw("'Payment' as type")
            );

    $paymentsQuery->where('debtors.company_id', $companyId);

        // Apply filters to payments
        if ($request->filled('debtor')) {
            $paymentsQuery->where('debtors.name', 'like', '%' . $request->debtor . '%');
        }

        if ($request->filled('from_date')) {
            $paymentsQuery->where('payments.paid_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $paymentsQuery->where('payments.paid_at', '<=', $request->to_date);
        }

        // Build adjustments query with filters
        $adjustmentsQuery = DB::table('balance_adjustments')
            ->join('debtors', 'balance_adjustments.debtor_id', '=', 'debtors.id')
            ->select(
                'balance_adjustments.adjusted_at as date',
                'balance_adjustments.amount',
                'balance_adjustments.note as description',
                DB::raw("NULL as voucher_no"),
                'debtors.name as debtor_name',
                DB::raw("CASE WHEN balance_adjustments.amount > 0 THEN 'Balance Addition' ELSE 'Balance Deduction' END as type")
            );

    $adjustmentsQuery->where('debtors.company_id', $companyId);

        // Apply filters to adjustments
        if ($request->filled('debtor')) {
            $adjustmentsQuery->where('debtors.name', 'like', '%' . $request->debtor . '%');
        }

        if ($request->filled('from_date')) {
            $adjustmentsQuery->where('balance_adjustments.adjusted_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $adjustmentsQuery->where('balance_adjustments.adjusted_at', '<=', $request->to_date);
        }

        // Union and get all records
        $transactions = $paymentsQuery
            ->union($adjustmentsQuery)
            ->orderBy('date', 'desc')
            ->get();

        $totalPayments = $transactions->where('type', 'Payment')->sum('amount');
        $totalAdditions = $transactions->where('type', 'Balance Addition')->sum('amount');
        $totalDeductions = $transactions->where('type', 'Balance Deduction')->sum('amount');

        $data = [
            'transactions' => $transactions,
            'totalPayments' => $totalPayments,
            'totalAdditions' => $totalAdditions,
            'totalDeductions' => $totalDeductions,
            'grandTotal' => $totalPayments + $totalAdditions,
            'filters' => $request->all(),
            'generatedDate' => now()->format('d M Y, h:i A'),
            'generatedBy' => Auth::user()->name,
        ];

        $pdf = Pdf::loadView('reports.all-transactions-pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        $filename = 'All_Transactions_Report_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
