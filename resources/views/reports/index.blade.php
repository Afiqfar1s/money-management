@extends('layouts.app')

@section('content')

<!-- Print Styles -->
<style>
    @media print {
        /* Force visibility */
        html, body {
            background: white !important;
            color: black !important;
            margin: 0;
            padding: 0;
            height: auto !important;
            overflow: visible !important;
        }
        
        /* Hide web elements */
        .no-print,
        nav,
        .container.mx-auto.px-4.py-6.no-print {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Show print-only elements */
        .print-only {
            display: block !important;
            visibility: visible !important;
        }
        
        /* Remove all shadows and rounded corners */
        * {
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        /* Print container */
        .print-container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* Print header */
        .print-header {
            display: flex !important;
            justify-content: space-between;
            align-items: start;
            padding: 20px;
            border-bottom: 3px solid #000;
            margin-bottom: 30px;
            background: white !important;
            page-break-inside: avoid;
        }
        
        .print-header-logo {
            max-width: 150px;
            max-height: 80px;
        }
        
        .print-header-info {
            text-align: right;
        }
        
        .print-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }
        
        .print-subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        
        /* Print content */
        .print-content {
            padding: 0 20px;
        }
        
        /* Make report container visible in print */
        .bg-white, .dark\:bg-gray-800 {
            background: white !important;
        }
        
        /* Remove borders and shadows from containers */
        .shadow-sm, .border, .rounded-lg {
            border: none !important;
            box-shadow: none !important;
        }
        
        /* Hide decorative backgrounds */
        .bg-gradient-to-r {
            background: white !important;
            border: none !important;
        }
        
        /* Make sure report content is visible */
        .report-print-content {
            display: block !important;
            visibility: visible !important;
            background: white !important;
            padding: 20px;
            color: black !important;
        }
        
        .report-print-content * {
            visibility: visible !important;
            color: black !important;
        }
        
        /* Hide report header on screen version */
        .report-header-screen {
            display: none !important;
        }
        
        /* Print tables */
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .print-table th {
            background: #f0f0f0 !important;
            color: #000 !important;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #000;
        }
        
        .print-table td {
            padding: 10px 8px;
            border: 1px solid #ccc;
            color: #000 !important;
        }
        
        .print-table tr:nth-child(even) {
            background: #f9f9f9 !important;
        }
        
        /* Print footer */
        .print-footer {
            margin-top: 30px;
            padding: 15px 20px;
            border-top: 2px solid #000;
            font-size: 10px;
            color: #666;
            display: flex !important;
            justify-content: space-between;
            background: white !important;
            page-break-inside: avoid;
        }
        
        /* Print summary boxes */
        .print-summary-box {
            border: 2px solid #000;
            padding: 15px;
            margin: 10px 0;
            page-break-inside: avoid;
        }
        
        .print-summary-label {
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }
        
        .print-summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-top: 5px;
        }
        
        /* Page breaks */
        .page-break {
            page-break-after: always;
        }
        
        /* Signature section */
        .print-signature {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }
        
        .print-signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 50px;
            padding-top: 5px;
        }
    }
    
    /* Hide print elements on screen */
    @media screen {
        .print-only {
            display: none;
        }
    }
</style>

<div class="container mx-auto px-4 py-6 no-print">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reports Center</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Generate and view various reports for your companies</p>
    </div>

    <!-- Selection Panel -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Company Selection -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Select Company
                    </label>
                    <select name="company_id" id="company_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" required onchange="this.form.submit()">
                        <option value="">-- Choose a Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $selectedCompany && $selectedCompany->id == $company->id ? 'selected' : '' }}>
                                {{ $company->name }} @if($company->code)({{ $company->code }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Report Type Selection -->
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Report Type
                    </label>
                    <select name="report_type" id="report_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        <option value="overview" {{ $reportType == 'overview' ? 'selected' : '' }}>📊 Overview Summary</option>
                        <option value="debtors" {{ $reportType == 'debtors' ? 'selected' : '' }}>👥 All Debtors List</option>
                        <option value="outstanding" {{ $reportType == 'outstanding' ? 'selected' : '' }}>💰 Outstanding Debts</option>
                        <option value="payments" {{ $reportType == 'payments' ? 'selected' : '' }}>💳 Payment History</option>
                    </select>
                </div>
            </div>

            @if($reportType == 'payments' && $selectedCompany)
            <!-- Date Filters for Payment History -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                        Apply Filters
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>

    @if($selectedCompany)
    
    <!-- Print-Only Header (Hidden on screen) -->
    <div class="print-only print-header">
        <div>
            @if($selectedCompany->logo_url)
                <img src="{{ $selectedCompany->logo_url }}" alt="{{ $selectedCompany->name }}" class="print-header-logo">
            @else
                <div style="font-size: 28px; font-weight: bold; color: #000;">{{ $selectedCompany->name }}</div>
            @endif
            <div style="margin-top: 10px; font-size: 12px; color: #666;">
                @if($selectedCompany->code)<strong>Code:</strong> {{ $selectedCompany->code }}<br>@endif
                @if($selectedCompany->phone)<strong>Phone:</strong> {{ $selectedCompany->phone }}<br>@endif
                @if($selectedCompany->address)<strong>Address:</strong> {{ $selectedCompany->address }}@endif
            </div>
        </div>
        <div class="print-header-info">
            <h1 class="print-title">
                @if($reportType == 'overview') OVERVIEW SUMMARY REPORT
                @elseif($reportType == 'debtors') ALL DEBTORS REPORT
                @elseif($reportType == 'outstanding') OUTSTANDING DEBTS REPORT
                @elseif($reportType == 'payments') PAYMENT HISTORY REPORT
                @endif
            </h1>
            <p class="print-subtitle">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            <p class="print-subtitle">Generated By: {{ Auth::user()->name }}</p>
            @if($reportType == 'payments' && (request('date_from') || request('date_to')))
                <p class="print-subtitle">
                    Period: 
                    @if(request('date_from'))
                        {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                    @else
                        All
                    @endif
                    to 
                    @if(request('date_to'))
                        {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                    @else
                        Present
                    @endif
                </p>
            @endif
        </div>
    </div>

    <!-- Print-Only Footer (Hidden on screen) -->
    <div class="print-only print-footer">
        <div>
            <strong>{{ $selectedCompany->name }}</strong> | {{ $selectedCompany->code ?? 'N/A' }}
        </div>
        <div>
            Page 1 of 1 | Confidential Document
        </div>
    </div>
    
    <!-- Report Content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Report Header (Hidden in Print) -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 report-header-screen no-print">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        @if($reportType == 'overview') 📊 Overview Summary
                        @elseif($reportType == 'debtors') 👥 All Debtors
                        @elseif($reportType == 'outstanding') 💰 Outstanding Debts
                        @elseif($reportType == 'payments') 💳 Payment History
                        @endif
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $selectedCompany->name }} @if($selectedCompany->code)• {{ $selectedCompany->code }}@endif
                    </p>
                </div>
                <div class="flex gap-2 no-print">
                    @php
                        $pdfUrl = '';
                        $queryParams = ['company_id' => $selectedCompany->id];
                        
                        if($reportType == 'overview') {
                            $pdfUrl = route('reports.download.overview', $queryParams);
                        } elseif($reportType == 'debtors') {
                            $pdfUrl = route('reports.download.debtors', $queryParams);
                        } elseif($reportType == 'outstanding') {
                            $pdfUrl = route('reports.download.outstanding', $queryParams);
                        } elseif($reportType == 'payments') {
                            $queryParams['date_from'] = request('date_from');
                            $queryParams['date_to'] = request('date_to');
                            $pdfUrl = route('reports.download.payments', $queryParams);
                        }
                    @endphp
                    
                    <a href="{{ $pdfUrl }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Data -->
        <div class="p-6 report-print-content">
            @if($reportType == 'overview')
                @include('reports.partials.overview', ['data' => $reportData])
            @elseif($reportType == 'debtors')
                @include('reports.partials.debtors', ['debtors' => $reportData])
            @elseif($reportType == 'outstanding')
                @include('reports.partials.outstanding', ['debtors' => $reportData])
            @elseif($reportType == 'payments')
                @include('reports.partials.payments', ['payments' => $reportData])
            @endif
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900 mb-4">
            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Select a Company to View Reports</h3>
        <p class="text-gray-600 dark:text-gray-400">Choose a company from the dropdown above to generate reports</p>
    </div>
    @endif
</div>
@endsection
