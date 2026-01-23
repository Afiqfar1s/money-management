<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment History Report</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #059669;
        }
        .company-logo {
            max-width: 120px;
            max-height: 60px;
            margin: 0 auto 10px;
        }
        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 9pt;
            color: #6B7280;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 16pt;
            color: #059669;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .report-meta {
            font-size: 8pt;
            color: #6B7280;
        }
        .filter-section {
            background-color: #F0FDF4;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #059669;
        }
        .filter-item {
            font-size: 8pt;
            color: #047857;
            margin-right: 15px;
            display: inline-block;
        }
        .summary-box {
            background-color: #F0FDF4;
            border-left: 4px solid #059669;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary-label {
            font-size: 9pt;
            color: #059669;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 20pt;
            font-weight: bold;
            color: #1F2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #059669;
            color: white;
        }
        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 8pt;
        }
        th.text-right {
            text-align: right;
        }
        tbody tr {
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) {
            background-color: #F0FDF4;
        }
        td {
            padding: 6px 6px;
            font-size: 8pt;
        }
        td.text-right {
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #6B7280;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($company->logo_url)
            <img src="{{ public_path('storage/' . str_replace('storage/', '', $company->logo_url)) }}" alt="{{ $company->name }}" class="company-logo">
        @endif
        <div class="company-name">{{ $company->name }}</div>
        <div class="company-details">
            @if($company->code)<strong>Code:</strong> {{ $company->code }} | @endif
            @if($company->phone)<strong>Phone:</strong> {{ $company->phone }} | @endif
            @if($company->address){{ $company->address }}@endif
        </div>
        <div class="report-title">PAYMENT HISTORY REPORT</div>
        <div class="report-meta">
            Generated: {{ $generatedDate }} | By: {{ $generatedBy }} | Total Payments: {{ $payments->count() }}
        </div>
    </div>

    <!-- Date Filter Display -->
    @if($dateFrom || $dateTo)
    <div class="filter-section">
        <strong style="font-size: 9pt;">Filtered Period:</strong>
        <span class="filter-item">
            <strong>From:</strong> {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : 'All' }}
        </span>
        <span class="filter-item">
            <strong>To:</strong> {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : 'Present' }}
        </span>
    </div>
    @endif

    <!-- Total Payments Summary -->
    <div class="summary-box">
        <span class="summary-label">TOTAL PAYMENTS RECEIVED</span>
        <span class="summary-value">RM {{ number_format($totalPayments, 2) }}</span>
    </div>

    <!-- Payments Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Debtor</th>
                <th>Voucher No.</th>
                <th>Description</th>
                <th class="text-right">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                <td><strong>{{ $payment->debtor_name }}</strong></td>
                <td>{{ $payment->voucher_no ?? '-' }}</td>
                <td>{{ $payment->note ?? 'Payment' }}</td>
                <td class="text-right"><strong style="color: #059669;">{{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #6B7280; padding: 20px;">
                    No payments found for the selected period
                </td>
            </tr>
            @endforelse
            @if($payments->count() > 0)
            <tr style="background-color: #D1FAE5; font-weight: bold;">
                <td colspan="5" style="text-align: right;"><strong>TOTAL:</strong></td>
                <td class="text-right" style="color: #059669;">{{ number_format($totalPayments, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Page 1 of 1 | {{ $company->name }} - Confidential Document
    </div>
</body>
</html>
