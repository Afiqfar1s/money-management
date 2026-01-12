<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History Report</title>
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4F46E5;
        }
        .company-name {
            font-size: 22pt;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16pt;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 9pt;
            color: #6B7280;
        }
        .info-section {
            margin-bottom: 25px;
            background-color: #F9FAFB;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #E5E7EB;
        }
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .info-label {
            font-weight: bold;
            color: #374151;
            width: 35%;
            display: table-cell;
        }
        .info-value {
            color: #1F2937;
            display: table-cell;
        }
        .summary-box {
            background-color: #EEF2FF;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #4F46E5;
            margin-bottom: 25px;
        }
        .summary-item {
            margin-bottom: 8px;
            font-size: 11pt;
        }
        .summary-label {
            font-weight: bold;
            color: #4338CA;
        }
        .summary-value {
            float: right;
            font-weight: bold;
            color: #1F2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        thead {
            background-color: #4F46E5;
            color: white;
        }
        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }
        tbody tr {
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        td {
            padding: 10px 8px;
            font-size: 9pt;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .date-col {
            width: 12%;
        }
        .type-col {
            width: 18%;
        }
        .voucher-col {
            width: 15%;
        }
        .amount-col {
            width: 15%;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
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
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #E5E7EB;
            font-size: 8pt;
            color: #6B7280;
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #6B7280;
            font-style: italic;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">MONEY MANAGEMENT SYSTEM</div>
        <div class="report-title">Payment History Report</div>
        <div class="report-subtitle">Detailed Transaction History</div>
    </div>

    <!-- Debtor Information -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">{{ $debtor->debtor_type === 'company' ? 'Company Name' : 'Debtor Name' }}:</span>
            <span class="info-value">{{ $debtor->name }}</span>
        </div>
        @if($debtor->ic_number)
        <div class="info-row">
            <span class="info-label">IC Number:</span>
            <span class="info-value">{{ $debtor->ic_number }}</span>
        </div>
        @endif
        @if($debtor->staff_number)
        <div class="info-row">
            <span class="info-label">Staff Number:</span>
            <span class="info-value">{{ $debtor->staff_number }}</span>
        </div>
        @endif
        @if($debtor->phone_number)
        <div class="info-row">
            <span class="info-label">Phone:</span>
            <span class="info-value">{{ $debtor->phone_number }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Current Balance:</span>
            <span class="info-value">RM {{ number_format((float) $debtor->outstanding, 2) }}</span>
        </div>
    </div>

    <!-- Summary -->
    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Total Amount Paid:</span>
            <span class="summary-value">RM {{ number_format($totalPaid, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Number of Transactions:</span>
            <span class="summary-value">{{ count($transactions) }}</span>
        </div>
    </div>

    <!-- Transactions Table -->
    @if(count($transactions) > 0)
    <table>
        <thead>
            <tr>
                <th class="date-col">Date</th>
                <th class="type-col">Type</th>
                <th>Description</th>
                <th class="voucher-col">Voucher No.</th>
                <th class="amount-col">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</td>
                <td>
                    @if($transaction['type'] === 'Payment')
                        <span class="badge badge-payment">{{ $transaction['type'] }}</span>
                    @elseif($transaction['type'] === 'Balance Addition')
                        <span class="badge badge-addition">{{ $transaction['type'] }}</span>
                    @else
                        <span class="badge badge-deduction">{{ $transaction['type'] }}</span>
                    @endif
                </td>
                <td>{{ $transaction['description'] }}</td>
                <td>{{ $transaction['voucher_no'] }}</td>
                <td class="amount">{{ number_format($transaction['amount'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        No transactions found for this debtor.
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>Generated on: {{ $generatedDate }}</div>
        <div>Generated by: {{ $generatedBy }}</div>
        <div style="margin-top: 5px;">This is a computer-generated document. No signature is required.</div>
    </div>
</body>
</html>
