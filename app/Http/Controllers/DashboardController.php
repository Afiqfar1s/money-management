<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // If admin, show admin dashboard
        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        }
        
        // For regular users, redirect to debtors list
        return redirect()->route('debtors.index');
    }
    
    private function adminDashboard(Request $request)
    {
        // Phase 1: Overview Statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        $totalCompanies = Company::count();
        $totalDebtors = Debtor::count();
        $totalOutstanding = Debtor::sum('outstanding');
        
        // Active sessions (last 15 minutes)
        $activeSessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
            ->count();
        
        // Total payments this month
        $paymentsThisMonth = DB::table('payments')
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
        
        // Total payments today
        $paymentsToday = DB::table('payments')
            ->whereDate('paid_at', now()->toDateString())
            ->sum('amount');
        
        // Phase 2: Company Performance Overview
        $companyPerformance = Company::withCount('debtors')
            ->withCount('users')
            ->get()
            ->map(function ($company) {
                $totalOutstanding = Debtor::where('company_id', $company->id)->sum('outstanding');
                
                $paymentsThisMonth = DB::table('payments')
                    ->join('debtors', 'payments.debtor_id', '=', 'debtors.id')
                    ->where('debtors.company_id', $company->id)
                    ->whereYear('payments.paid_at', now()->year)
                    ->whereMonth('payments.paid_at', now()->month)
                    ->sum('payments.amount');
                
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'code' => $company->code,
                    'logo_path' => $company->logo_path,
                    'debtors_count' => $company->debtors_count,
                    'users_count' => $company->users_count,
                    'total_outstanding' => $totalOutstanding,
                    'payments_this_month' => $paymentsThisMonth,
                ];
            })
            ->sortByDesc('total_outstanding');
        
        // Phase 3: Recent Activity (Last 20 activities)
        $recentActivities = $this->getRecentActivities();
        
        // Additional: Top Debtors (System-Wide)
        $topDebtors = Debtor::with('company')
            ->orderBy('outstanding', 'desc')
            ->limit(10)
            ->get();
        
        // System Health Alerts
        $alerts = $this->getSystemAlerts();
        
        return view('dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'totalCompanies',
            'totalDebtors',
            'totalOutstanding',
            'activeSessions',
            'paymentsThisMonth',
            'paymentsToday',
            'companyPerformance',
            'recentActivities',
            'topDebtors',
            'alerts'
        ));
    }
    
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Recent user logins (from sessions)
        $recentLogins = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select('users.name', 'sessions.last_activity', 'sessions.ip_address')
            ->orderBy('sessions.last_activity', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($login) {
                return [
                    'type' => 'login',
                    'icon' => 'login',
                    'description' => "{$login->name} logged in",
                    'timestamp' => Carbon::createFromTimestamp($login->last_activity),
                    'meta' => "from {$login->ip_address}",
                ];
            });
        
        // Recent debtors
        $recentDebtors = Debtor::with('company')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($debtor) {
                return [
                    'type' => 'debtor',
                    'icon' => 'user-plus',
                    'description' => "New debtor added: {$debtor->name}",
                    'timestamp' => $debtor->created_at,
                    'meta' => "in {$debtor->company->name}",
                ];
            });
        
        // Recent payments
        $recentPayments = DB::table('payments')
            ->join('debtors', 'payments.debtor_id', '=', 'debtors.id')
            ->join('companies', 'debtors.company_id', '=', 'companies.id')
            ->select(
                'payments.amount',
                'payments.paid_at',
                'debtors.name as debtor_name',
                'companies.name as company_name',
                'payments.created_at'
            )
            ->orderBy('payments.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'icon' => 'cash',
                    'description' => "Payment received: RM " . number_format($payment->amount, 2),
                    'timestamp' => Carbon::parse($payment->created_at),
                    'meta' => "from {$payment->debtor_name}",
                ];
            });
        
        // Recent balance adjustments
        $recentAdjustments = DB::table('balance_adjustments')
            ->join('debtors', 'balance_adjustments.debtor_id', '=', 'debtors.id')
            ->select(
                'balance_adjustments.amount',
                'debtors.name as debtor_name',
                'balance_adjustments.created_at'
            )
            ->orderBy('balance_adjustments.created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($adjustment) {
                $type = $adjustment->amount >= 0 ? 'increased' : 'decreased';
                return [
                    'type' => 'adjustment',
                    'icon' => 'adjustment',
                    'description' => "Balance {$type}: RM " . number_format(abs($adjustment->amount), 2),
                    'timestamp' => Carbon::parse($adjustment->created_at),
                    'meta' => "for {$adjustment->debtor_name}",
                ];
            });
        
        // Merge and sort all activities
        return $activities
            ->merge($recentLogins)
            ->merge($recentDebtors)
            ->merge($recentPayments)
            ->merge($recentAdjustments)
            ->sortByDesc('timestamp')
            ->take(15);
    }
    
    private function getSystemAlerts()
    {
        $alerts = [];
        
        // Users without company assignments
        $usersWithoutCompanies = User::where('role', 'user')
            ->whereDoesntHave('companies')
            ->count();
        
        if ($usersWithoutCompanies > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'exclamation',
                'message' => "{$usersWithoutCompanies} user(s) without company assignments",
                'action_url' => route('users.index'),
                'action_text' => 'View Users',
            ];
        }
        
        // Debtors with high outstanding (> 100,000)
        $highOutstandingCount = Debtor::where('outstanding', '>', 100000)->count();
        
        if ($highOutstandingCount > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'info',
                'message' => "{$highOutstandingCount} debtor(s) with outstanding > RM 100,000",
                'action_url' => null,
                'action_text' => null,
            ];
        }
        
        // Companies with no users
        $companiesWithoutUsers = Company::whereDoesntHave('users')->count();
        
        if ($companiesWithoutUsers > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'exclamation',
                'message' => "{$companiesWithoutUsers} company(ies) with no assigned users",
                'action_url' => route('companies.index'),
                'action_text' => 'View Companies',
            ];
        }
        
        // Companies with no debtors
        $companiesWithoutDebtors = Company::whereDoesntHave('debtors')->count();
        
        if ($companiesWithoutDebtors > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'info',
                'message' => "{$companiesWithoutDebtors} company(ies) with no debtors yet",
                'action_url' => null,
                'action_text' => null,
            ];
        }
        
        return $alerts;
    }
}
