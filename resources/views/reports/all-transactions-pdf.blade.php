<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Transactions Report</title>
    <style>
        @page {
            margin: 20px;
            size: landscape;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #4F46E5;
        }
        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 3px;
        }
        .report-title {
            font-size: 14pt;
            color: #4F46E5;
            margin-bottom: 3px;
        }
        .report-subtitle {
            font-size: 8pt;
            color: #6B7280;
        }
        .filters-section {
            background-color: #F9FAFB;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
            border: 1px solid #E5E7EB;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            font-size: 8pt;
        }
        .filter-label {
            font-weight: bold;
            color: #374151;
        }
        .summary-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-box {
            display: table-cell;
            background-color: #EEF2FF;
            padding: 10px;
            border-radius: 3px;
            border-left: 3px solid #4F46E5;
            width: 24%;
            margin-right: 1%;
        }
        .summary-label {
            font-size: 7pt;
            color: #4338CA;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 11pt;
            font-weight: bold;
            color: #1F2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        thead {
            background-color: #4F46E5;
            color: white;
        }
        th {
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 7pt;
        }
        tbody tr {
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        td {
            padding: 6px 4px;
            font-size: 7pt;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
    .date-col { width: 8%; }
    .debtor-col { width: 24%; }
        .type-col { width: 12%; }
        .voucher-col { width: 10%; }
        .amount-col { width: 10%; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 6pt;
            font-weight: bold;
        }
        .badge-payment {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-addition {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        .badge-deduction {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #E5E7EB;
            font-size: 7pt;
            color: #6B7280;
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #6B7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">MONEY MANAGEMENT SYSTEM</div>
        <div class="report-title">Complete Transaction Report</div>
        <div class="report-subtitle">All Transactions Across All Users (Administrator Report)</div>
    </div>

    <!-- Filters Applied -->
    @if(!empty($filters))
    <div class="filters-section">
        <strong>Filters Applied:</strong>
        @if(isset($filters['user']))
            <span class="filter-item"><span class="filter-label">User:</span> {{ $filters['user'] }}</span>
        @endif
        @if(isset($filters['debtor']))
            <span class="filter-item"><span class="filter-label">Debtor:</span> {{ $filters['debtor'] }}</span>
        @endif
        @if(isset($filters['from_date']))
            <span class="filter-item"><span class="filter-label">From:</span> {{ \Carbon\Carbon::parse($filters['from_date'])->format('d/m/Y') }}</span>
        @endif
        @if(isset($filters['to_date']))
            <span class="filter-item"><span class="filter-label">To:</span> {{ \Carbon\Carbon::parse($filters['to_date'])->format('d/m/Y') }}</span>
        @endif
        @if(empty($filters['user']) && empty($filters['debtor']) && empty($filters['from_date']) && empty($filters['to_date']))
            <span>No filters applied - showing all transactions</span>
        @endif
    </div>
    @endif

    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-box">
            <span class="summary-label">TOTAL PAYMENTS</span>
            <div class="summary-value">RM {{ number_format($totalPayments, 2) }}</div>
        </div>
        <div class="summary-box" style="margin-left: 1%;">
            <span class="summary-label">TOTAL ADDITIONS</span>
            <div class="summary-value">RM {{ number_format($totalAdditions, 2) }}</div>
        </div>
        <div class="summary-box" style="margin-left: 1%;">
            <span class="summary-label">TOTAL DEDUCTIONS</span>
            <div class="summary-value">RM {{ number_format($totalDeductions, 2) }}</div>
        </div>
        <div class="summary-box" style="margin-left: 1%;">
            <span class="summary-label">GRAND TOTAL</span>
            <div class="summary-value">RM {{ number_format($grandTotal, 2) }}</div>
        </div>
    </div>

    <!-- Transactions Table -->
    @if(count($transactions) > 0)
    <table>
        <thead>
            <tr>
                <th class="date-col">Date</th>
                <th class="debtor-col">Debtor / Company</th>
                <th class="type-col">Type</th>
                <th>Description</th>
                <th class="voucher-col">Voucher No.</th>
                <th class="amount-col">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $transaction->debtor_name }}</strong>
                </td>
                <td>
                    @if($transaction->type === 'Payment')
                        <span class="badge badge-payment">{{ $transaction->type }}</span>
                    @elseif($transaction->type === 'Balance Addition')
                        <span class="badge badge-addition">Addition</span>
                    @else
                        <span class="badge badge-deduction">Deduction</span>
                    @endif
                </td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->voucher_no ?? '-' }}</td>
                <td class="amount">{{ number_format($transaction->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: right; font-weight: bold; font-size: 9pt; margin-top: 10px;">
        Total Transactions: {{ count($transactions) }}
    </div>
    @else
    <div class="no-data">
        No transactions found matching the selected criteria.
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>Generated on: {{ $generatedDate }}</div>
        <div>Generated by: {{ $generatedBy }} (Administrator)</div>
        <div style="margin-top: 5px;">This is a confidential administrator report. Please handle with care.</div>
    </div>
</body>
</html>
