<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Debtors Report</title>
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
            font-size: 8pt;
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
        <div class="report-title">ALL DEBTORS REPORT</div>
        <div class="report-meta">
            Generated: {{ $generatedDate }} | By: {{ $generatedBy }} | Total Debtors: {{ $debtors->count() }}
        </div>
    </div>

    <!-- Debtors Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Debtor Name</th>
                <th>Code</th>
                <th>Contact</th>
                <th class="text-right">Outstanding (RM)</th>
                <th class="text-right">Total Paid (RM)</th>
                <th class="text-right">Total Debt (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debtors as $index => $debtor)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $debtor->name }}</strong></td>
                <td>{{ $debtor->code ?? '-' }}</td>
                <td>{{ $debtor->phone ?? '-' }}</td>
                <td class="text-right">{{ number_format($debtor->outstanding, 2) }}</td>
                <td class="text-right">{{ number_format($debtor->total_paid, 2) }}</td>
                <td class="text-right"><strong>{{ number_format($debtor->outstanding + $debtor->total_paid, 2) }}</strong></td>
            </tr>
            @endforeach
            <tr style="background-color: #EEF2FF; font-weight: bold;">
                <td colspan="4" style="text-align: right;"><strong>TOTALS:</strong></td>
                <td class="text-right">{{ number_format($debtors->sum('outstanding'), 2) }}</td>
                <td class="text-right">{{ number_format($debtors->sum('total_paid'), 2) }}</td>
                <td class="text-right">{{ number_format($debtors->sum('outstanding') + $debtors->sum('total_paid'), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Page 1 of 1 | {{ $company->name }} - Confidential Document
    </div>
</body>
</html>
