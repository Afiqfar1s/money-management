<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Outstanding Debts Report</title>
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
            border-bottom: 3px solid #DC2626;
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
            color: #DC2626;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .report-meta {
            font-size: 8pt;
            color: #6B7280;
        }
        .summary-box {
            background-color: #FEF2F2;
            border-left: 4px solid #DC2626;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary-label {
            font-size: 9pt;
            color: #DC2626;
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
            background-color: #DC2626;
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
            background-color: #FEF2F2;
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
        <div class="report-title">OUTSTANDING DEBTS REPORT</div>
        <div class="report-meta">
            Generated: {{ $generatedDate }} | By: {{ $generatedBy }} | Debtors with Outstanding: {{ $debtors->count() }}
        </div>
    </div>

    <!-- Total Outstanding Summary -->
    <div class="summary-box">
        <span class="summary-label">TOTAL OUTSTANDING AMOUNT</span>
        <span class="summary-value">RM {{ number_format($totalOutstanding, 2) }}</span>
    </div>

    <!-- Outstanding Debtors Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Debtor Name</th>
                <th>Code</th>
                <th>Contact</th>
                <th class="text-right">Outstanding (RM)</th>
                <th class="text-right">Total Paid (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debtors as $index => $debtor)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $debtor->name }}</strong></td>
                <td>{{ $debtor->code ?? '-' }}</td>
                <td>{{ $debtor->phone ?? '-' }}</td>
                <td class="text-right"><strong style="color: #DC2626;">{{ number_format($debtor->outstanding, 2) }}</strong></td>
                <td class="text-right">{{ number_format($debtor->total_paid, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #FEE2E2; font-weight: bold;">
                <td colspan="4" style="text-align: right;"><strong>TOTALS:</strong></td>
                <td class="text-right" style="color: #DC2626;">{{ number_format($debtors->sum('outstanding'), 2) }}</td>
                <td class="text-right">{{ number_format($debtors->sum('total_paid'), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Page 1 of 1 | {{ $company->name }} - Confidential Document
    </div>
</body>
</html>
