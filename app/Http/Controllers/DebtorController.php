<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
{
    public function index(Request $request)
    {
        // Check permission for viewing debtors
        $user = auth()->user();
        
        // Admin sees all debtors, users with view_all_debtors permission see all, otherwise see only their own
        if ($user->isAdmin() || $user->hasPermission('view_all_debtors')) {
            $query = Debtor::query();
        } else {
            if (!$user->hasPermission('view_own_debtors')) {
                abort(403, 'You do not have permission to view debtors.');
            }
            $query = Debtor::where('user_id', auth()->id());
        }

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

        $debtors = $query->with('user')
            ->withMax('payments', 'paid_at')
            ->orderBy('name', 'asc')
            ->paginate(20)
            ->withQueryString();

        // Compute summary (ignore filters)
        if ($user->isAdmin() || $user->hasPermission('view_all_debtors')) {
            $summaryQuery = Debtor::query();
        } else {
            $summaryQuery = Debtor::where('user_id', auth()->id());
        }
        
        $total_outstanding = $summaryQuery->sum('outstanding');
        $total_debtors = $summaryQuery->count();
        
        $paymentsQuery = DB::table('payments')
            ->join('debtors', 'payments.debtor_id', '=', 'debtors.id');
        
        if (!$user->isAdmin() && !$user->hasPermission('view_all_debtors')) {
            $paymentsQuery->where('debtors.user_id', auth()->id());
        }
        
        $total_paid = $paymentsQuery->sum('payments.amount');

        return view('debtors.index', compact('debtors', 'total_outstanding', 'total_paid', 'total_debtors'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('create_debtors')) {
            abort(403, 'You do not have permission to create debtors.');
        }
        
        return view('debtors.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('create_debtors')) {
            abort(403, 'You do not have permission to create debtors.');
        }
        
        $validated = $request->validate([
            'debtor_type' => 'required|in:individual,company',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_outstanding' => 'required|numeric|min:0',
            'voucher_no' => 'nullable|string|max:255|unique:balance_adjustments,voucher_no',
            
            // Individual fields
            'staff_number' => 'required_if:debtor_type,individual|nullable|string|max:255',
            'ic_number' => 'required_if:debtor_type,individual|nullable|string|max:255',
            'phone_number' => 'required_if:debtor_type,individual|nullable|string|max:255',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'start_working_date' => 'required_if:debtor_type,individual|nullable|date',
            'resign_date' => 'nullable|date|after_or_equal:start_working_date',
            
            // Company fields
            'ssm_number' => 'required_if:debtor_type,company|nullable|string|max:255',
            'office_phone' => 'required_if:debtor_type,company|nullable|string|max:255',
            'company_address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $debtor = Debtor::create([
                'user_id' => auth()->id(),
                'debtor_type' => $validated['debtor_type'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'starting_outstanding' => $validated['starting_outstanding'],
                'outstanding' => $validated['starting_outstanding'],
                
                // Individual fields
                'staff_number' => $validated['staff_number'] ?? null,
                'ic_number' => $validated['ic_number'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'position' => $validated['position'] ?? null,
                'start_working_date' => $validated['start_working_date'] ?? null,
                'resign_date' => $validated['resign_date'] ?? null,
                
                // Company fields
                'ssm_number' => $validated['ssm_number'] ?? null,
                'office_phone' => $validated['office_phone'] ?? null,
                'company_address' => $validated['company_address'] ?? null,
            ]);

            // Create initial balance adjustment if voucher number is provided and starting outstanding > 0
            if (!empty($validated['voucher_no']) && $validated['starting_outstanding'] > 0) {
                \App\Models\BalanceAdjustment::create([
                    'debtor_id' => $debtor->id,
                    'voucher_no' => $validated['voucher_no'],
                    'amount' => $validated['starting_outstanding'],
                    'note' => 'Initial debt record',
                    'adjusted_at' => now(),
                ]);
            }
        });

        $debtor = Debtor::where('user_id', auth()->id())->latest()->first();

        return redirect()->route('debtors.show', $debtor)->with('success', 'Debtor created successfully.');
    }

    public function show(Debtor $debtor)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->hasPermission('view_all_debtors') && $debtor->user_id !== auth()->id()) {
            abort(404);
        }

        if (!$user->isAdmin() && !$user->hasPermission('view_all_debtors') && !$user->hasPermission('view_own_debtors')) {
            abort(403, 'You do not have permission to view debtors.');
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
        $user = auth()->user();

        if ($user->isAdmin() || $user->hasPermission('edit_all_debtors')) {
            return view('debtors.edit', compact('debtor'));
        }

        if (!$user->hasPermission('edit_own_debtors')) {
            abort(403, 'You do not have permission to edit debtors.');
        }

        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        return view('debtors.edit', compact('debtor'));
    }

    public function update(Request $request, Debtor $debtor)
    {
        $user = auth()->user();

        if (!($user->isAdmin() || $user->hasPermission('edit_all_debtors'))) {
            if (!$user->hasPermission('edit_own_debtors')) {
                abort(403, 'You do not have permission to edit debtors.');
            }

            if ($debtor->user_id !== auth()->id()) {
                abort(404);
            }
        }

        $validated = $request->validate([
            'debtor_type' => 'required|in:individual,company',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_outstanding' => 'required|numeric|min:0',
            
            // Individual fields
            'staff_number' => 'required_if:debtor_type,individual|nullable|string|max:255',
            'ic_number' => 'required_if:debtor_type,individual|nullable|string|max:255',
            'phone_number' => 'required_if:debtor_type,individual|nullable|string|max:255',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'start_working_date' => 'required_if:debtor_type,individual|nullable|date',
            'resign_date' => 'nullable|date|after_or_equal:start_working_date',
            
            // Company fields
            'ssm_number' => 'required_if:debtor_type,company|nullable|string|max:255',
            'office_phone' => 'required_if:debtor_type,company|nullable|string|max:255',
            'company_address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($debtor, $validated) {
            $debtor->lockForUpdate()->first();

            $debtor->update([
                'debtor_type' => $validated['debtor_type'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'starting_outstanding' => $validated['starting_outstanding'],
                
                // Individual fields
                'staff_number' => $validated['staff_number'] ?? null,
                'ic_number' => $validated['ic_number'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'position' => $validated['position'] ?? null,
                'start_working_date' => $validated['start_working_date'] ?? null,
                'resign_date' => $validated['resign_date'] ?? null,
                
                // Company fields
                'ssm_number' => $validated['ssm_number'] ?? null,
                'office_phone' => $validated['office_phone'] ?? null,
                'company_address' => $validated['company_address'] ?? null,
            ]);

            $total_paid = $debtor->payments()->sum('amount');
            $debtor->outstanding = max(0, $debtor->starting_outstanding - $total_paid);
            $debtor->save();
        });

        return redirect()->route('debtors.show', $debtor)->with('success', 'Debtor updated successfully.');
    }

    public function refresh(Debtor $debtor)
    {
        $user = auth()->user();

        if (!($user->isAdmin() || $user->hasPermission('edit_all_debtors'))) {
            if (!$user->hasPermission('edit_own_debtors')) {
                abort(403, 'You do not have permission to refresh debtors.');
            }

            if ($debtor->user_id !== auth()->id()) {
                abort(404);
            }
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

        $user = auth()->user();

        if ($user->isAdmin() || $user->hasPermission('view_all_debtors')) {
            $query = Debtor::query();
        } else {
            if (!$user->hasPermission('view_own_debtors')) {
                abort(403, 'You do not have permission to view debtors.');
            }
            $query = Debtor::where('user_id', auth()->id());
        }

        $query->chunkById(100, function ($debtors) use (&$count) {
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

    public function destroy(Debtor $debtor)
    {
        $user = auth()->user();

        // Admins can delete any debtor
        if ($user->isAdmin()) {
            $debtor->delete();

            return redirect()->route('debtors.index')
                ->with('success', 'Debtor deleted successfully.');
        }

        // Non-admin users: must have permission + ownership (unless granted delete_all_debtors)
        if ($user->hasPermission('delete_all_debtors')) {
            $debtor->delete();

            return redirect()->route('debtors.index')
                ->with('success', 'Debtor deleted successfully.');
        }

        if (!$user->hasPermission('delete_own_debtors')) {
            abort(403, 'You do not have permission to delete debtors.');
        }

        if ($debtor->user_id !== auth()->id()) {
            abort(404);
        }

        $debtor->delete();

        return redirect()->route('debtors.index')
            ->with('success', 'Debtor deleted successfully.');
    }
}
