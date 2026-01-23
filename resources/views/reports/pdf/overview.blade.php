<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview Summary Report</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4F46E5;
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
            color: #4F46E5;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .report-meta {
            font-size: 8pt;
            color: #6B7280;
        }
        .summary-section {
            margin-bottom: 25px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-box {
            display: table-cell;
            background-color: #EEF2FF;
            padding: 15px;
            border-radius: 3px;
            border-left: 4px solid #4F46E5;
            width: 32%;
            margin-right: 2%;
        }
        .summary-box:last-child {
            margin-right: 0;
        }
        .summary-label {
            font-size: 8pt;
            color: #4338CA;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 18pt;
            font-weight: bold;
            color: #1F2937;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #E5E7EB;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #4F46E5;
            color: white;
        }
        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }
        th.text-right {
            text-align: right;
        }
        tbody tr {
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        td {
            padding: 8px 6px;
            font-size: 9pt;
        }
        td.text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
        }
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 60px auto 10px;
            padding-top: 5px;
            font-size: 9pt;
            font-weight: bold;
        }
        .page-number {
            text-align: center;
            font-size: 8pt;
            color: #6B7280;
            margin-top: 20px;
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
        <div class="report-title">OVERVIEW SUMMARY REPORT</div>
        <div class="report-meta">
            Generated: {{ $generatedDate }} | By: {{ $generatedBy }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-box">
                <span class="summary-label">Total Debtors</span>
                <span class="summary-value">{{ $totalDebtors }}</span>
            </div>
            <div class="summary-box" style="border-left-color: #DC2626;">
                <span class="summary-label" style="color: #DC2626;">Total Outstanding</span>
                <span class="summary-value">RM {{ number_format($totalOutstanding, 2) }}</span>
            </div>
            <div class="summary-box" style="border-left-color: #059669;">
                <span class="summary-label" style="color: #059669;">Total Paid</span>
                <span class="summary-value">RM {{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="section-title">Recent Payments (Last 10)</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Debtor</th>
                <th>Voucher No.</th>
                <th>Description</th>
                <th class="text-right">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPayments as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                <td>{{ $payment->debtor_name }}</td>
                <td>{{ $payment->voucher_no ?? '-' }}</td>
                <td>{{ $payment->note ?? 'Payment' }}</td>
                <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #6B7280;">No payments recorded</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Prepared By</div>
            <div style="font-size: 8pt; color: #6B7280;">{{ $generatedBy }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Approved By</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="page-number">
        Page 1 of 1 | {{ $company->name }} - Confidential Document
    </div>
</body>
</html>
