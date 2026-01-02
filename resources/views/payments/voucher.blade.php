<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher - {{ $payment->voucher_no }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto py-12 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8 border-b pb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Voucher</h1>
                <p class="text-lg text-gray-600">{{ $payment->voucher_no }}</p>
            </div>

            <!-- Voucher Details -->
            <div class="space-y-4 mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Debtor Name</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $payment->debtor->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Voucher Number</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $payment->voucher_no }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Paid Date/Time</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $payment->paid_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Amount Paid</div>
                        <div class="text-2xl font-bold text-green-600">RM {{ number_format((float)$payment->amount, 2) }}</div>
                    </div>
                </div>

                @if($payment->note)
                <div>
                    <div class="text-sm text-gray-600 mb-1">Note</div>
                    <div class="text-lg text-gray-900">{{ $payment->note }}</div>
                </div>
                @endif

                <div>
                    <div class="text-sm text-gray-600 mb-1">Created At</div>
                    <div class="text-lg text-gray-900">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t pt-6 mt-8">
                <div class="text-sm text-gray-500 text-center">
                    This is a computer-generated voucher and does not require a signature.
                </div>
            </div>

            <!-- Print Button -->
            <div class="mt-8 text-center no-print">
                <button onclick="window.print()" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold">
                    Print Voucher
                </button>
                <a href="{{ route('debtors.show', $payment->debtor) }}" class="ml-4 px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-semibold inline-block">
                    Back to Debtor
                </a>
            </div>
        </div>
    </div>
</body>
</html>
