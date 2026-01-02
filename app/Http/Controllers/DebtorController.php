<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
{
    public function index(Request $request)
    {
        $query = Debtor::where('user_id', auth()->id());

        // Search filter
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'owing') {
                $query->where('outstanding', '>', 0);
            } elseif ($request->status === 'settled') {
                $query->where('outstanding', '=', 0);
            }
        }

        $debtors = $query->withMax('payments', 'paid_at')
            ->orderBy('name', 'asc')
            ->paginate(20)
            ->withQueryString();

        // Compute summary (ignore filters)
        $summaryQuery = Debtor::where('user_id', auth()->id());
        
        $total_outstanding = $summaryQuery->sum('outstanding');
        $total_debtors = $summaryQuery->count();
        
        $total_paid = DB::table('payments')
            ->join('debtors', 'payments.debtor_id', '=', 'debtors.id')
            ->where('debtors.user_id', auth()->id())
            ->sum('payments.amount');

        return view('debtors.index', compact('debtors', 'total_outstanding', 'total_paid', 'total_debtors'));
    }

    public function create()
    {
        return view('debtors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_outstanding' => 'required|numeric|min:0',
        ]);

        $debtor = Debtor::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'starting_outstanding' => $validated['starting_outstanding'],
            'outstanding' => $validated['starting_outstanding'],
        ]);

        return redirect()->route('debtors.show', $debtor)->with('success', 'Debtor created successfully.');
    }

    public function show(Debtor $debtor)
    {
        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        $payments = $debtor->payments()
            ->orderBy('paid_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(25, ['*'], 'payments_page');

        $adjustments = $debtor->balanceAdjustments()
            ->orderBy('adjusted_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(25, ['*'], 'adjustments_page');

        $total_paid = $debtor->payments()->sum('amount');

        return view('debtors.show', compact('debtor', 'payments', 'adjustments', 'total_paid'));
    }

    public function edit(Debtor $debtor)
    {
        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        return view('debtors.edit', compact('debtor'));
    }

    public function update(Request $request, Debtor $debtor)
    {
        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_outstanding' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor->lockForUpdate()->first();

            $debtor->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'starting_outstanding' => $validated['starting_outstanding'],
            ]);

            $total_paid = $debtor->payments()->sum('amount');
            $debtor->outstanding = max(0, $debtor->starting_outstanding - $total_paid);
            $debtor->save();
        });

        return redirect()->route('debtors.show', $debtor)->with('success', 'Debtor updated successfully.');
    }

    public function refresh(Debtor $debtor)
    {
        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        DB::transaction(function () use ($debtor) {
            $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

            $total_paid = $debtor->payments()->sum('amount');
            $debtor->outstanding = max(0, $debtor->starting_outstanding - $total_paid);
            $debtor->save();
        });

        return redirect()->back()->with('success', 'Balance refreshed successfully.');
    }

    public function refreshAll()
    {
        $count = 0;

        Debtor::where('user_id', auth()->id())->chunkById(100, function ($debtors) use (&$count) {
            foreach ($debtors as $debtor) {
                DB::transaction(function () use ($debtor) {
                    $debtor = Debtor::where('id', $debtor->id)->lockForUpdate()->first();

                    $total_paid = $debtor->payments()->sum('amount');
                    $debtor->outstanding = max(0, $debtor->starting_outstanding - $total_paid);
                    $debtor->save();
                });
                $count++;
            }
        });

        return redirect()->back()->with('success', "Refreshed {$count} debtor(s) successfully.");
    }
}
