<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ public_path('uploads/favicon/' . gs()->favicon) }}">
    <title>Invoice</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f5f5f5;
        }

        /* Remove border from header table */
        .header td,
        .header th {
            border: none;
        }

        /* Remove border from customer-supplier table */
        .customer-supplier td,
        .customer-supplier th {
            border: none;
        }

        .header td {
            vertical-align: top;
        }

        .header .logo {
            max-width: 100px;
        }

        .header .details {
            text-align: right;
        }

        .details p {
            margin: 0;
        }

        .customer-supplier td {
            vertical-align: top;
        }

        .customer-supplier .supplier-details {
            width: 48%;
        }

        .customer-supplier .customer-details {
            width: 48%;
            text-align: right;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }

        /* Center-align data in Payment and Product Details tables */
        .payment-table td,
        .payment-table th,
        .bank-table td,
        .bank-table th,
        .product-details-table td,
        .product-details-table th {
            text-align: center;
        }

        .payment-summary {
            margin-top: 20px;
            padding: 5px;
            width: 300px;
            margin-left: auto;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-value {
            min-width: 100px;
            text-align: right;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <table class="header">
            <tr>
                <td>
                    <img src="{{public_path('uploads/logo/' . gs()->logo)}}" alt="Logo" class="logo" width="100">
                    <h4 style="margin: 0;">{{ gs()->company_name }}</h4>
                </td>
                <td class="details">
                    <p>Invoice #: {{ $sell->id }}</p>
                    <p>Created: {{ showDateTime($sell->created_at) }}</p>
                </td>
            </tr>
        </table>

        <!-- Supplier and Customer Details -->
        <table class="customer-supplier">
            <tr>
                <td class="supplier-details">
                    <strong>Customer Details</strong>
                    <p>Name: {{ $sell->customer->name }} ({{ $sell->customer->customerType->name }})</p>
                    <p>Code: {{ $sell->customer->code }}</p>
                    <p>Company: {{ $sell->customer->company }}</p>
                    <p>Mobile: {{ $sell->customer->mobile }}</p>
                    <p>Email: {{ $sell->customer->email }}</p>
                    <p>Address: {{ $sell->customer->address }}</p>
                </td>
            </tr>
        </table>

        <!-- Payment Details -->
        {{--<table class="payment-table">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Due</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sell->account->payment_method == 1 ? 'Cash' : 'Bank' }}</td>
                    <td>{{ showAmount($sell->total_price) }} Tk</td>
                    <td>{{ showAmount($sell->due_to_company) }} Tk</td>
                </tr>
            </tbody>
        </table>

        <!-- Payment Details -->
        @if ($sell->account->payment_method == 2)
            <table class="bank-table">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Check No</th>
                        <th>Withdrawer Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $sell->account->bankTransaction->bank->bank_name }}</td>
                        <td>{{ $sell->account->bankTransaction->check_no }}</td>
                        <td>{{ $sell->account->bankTransaction->depositor_name }}</td>
                    </tr>
                </tbody>
            </table>
        @endif--}}

        <!-- Invoice Items -->
        <table class="product-details-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Product Name</th>
                    <th>Sell Rate</th>
                    <th>Quantity</th>
                    <th>Discount</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sell->sellRecords as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ showAmount($item->avg_sell_price) }} Tk</td>
                        <td>{{ $item->sell_qty . ' ' . $item->product->unit->name }}</td>
                        <td>{{ showAmount($item->discount) }} %</td>
                        <td>{{ showAmount($item->total_amount) }} Tk</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="total">Total:</td>
                    <td>{{ showAmount($sell->total_price) }} Tk</td>
                </tr>
            </tfoot>
        </table>


        <!-- Total Payment Summary -->
        <div class="payment-summary" style="text-align: right;">
            <div class="summary-line">
                <span class="summary-label">Subtotal:</span>
                <span class="summary-value">{{ showAmount($sell->total_price) }} Tk</span>
            </div>
            <div class="summary-line">
                <span class="summary-label">Discount:</span>
                <span class="summary-value">{{ showAmount($sell->discount ?? 0) }} Tk</span>
            </div>
            <div class="summary-line total">
                <span class="summary-label">Total Payable:</span>
                <span class="summary-value">{{ showAmount($sell->total_price) }} Tk</span>
            </div>
            <div class="summary-line">
                <span class="summary-label">Due:</span>
                <span class="summary-value">{{ showAmount($sell->due_to_company) }} Tk</span>
            </div>
            <hr>
            <div class="summary-line total">
                <span class="summary-label">Total Paid:</span>
                <span class="summary-value">{{ showAmount($sell->payment_received) }} Tk</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
