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
                    <img src="{{ public_path('uploads/logo/' . gs()->logo) }}" alt="Logo" class="logo"
                        width="100">
                    <h4 style="margin: 0;">{{ gs()->company_name }}</h4>
                </td>
                <td class="details">

                    <strong>Supplier Details</strong>
                    <p>{{ !empty($supplierData->name) ? $supplierData->name : '-' }}</p>
                    <p>{{ !empty($supplierData->code) ? $supplierData->code : '-' }}</p>
                    <p>{{ !empty($supplierData->company) ? $supplierData->company : '-' }}</p>
                    <p>{{ !empty($supplierData->mobile) ? $supplierData->mobile : '-' }}</p>
                    <p>{{ !empty($supplierData->address) ? $supplierData->address : '-' }}</p>
                    <p>{{ !empty($supplierData->email) ? $supplierData->email : '-' }}</p>
                    <p>Advance : {{ !empty(showAmount($supplierData->advance)) ? showAmount($supplierData->advance) : '-' }}</p>
                    
                </td>
            </tr>
        </table>

        <!-- Invoice Items -->
        <table class="product-details-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Invoice Number</th>
                    <th class="text-center">Total Qty</th>
                    <th class="text-center">Main Price</th>
                    <th class="text-center">Commission</th>
                    <th class="text-center">Discount</th>
                    <th class="text-center">Total Price</th>
                    <th class="text-center">Payment</th>
                    <th class="text-center">Due</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalQty = 0;
                    $totalMainPrice = 0;
                    $totalCommission = 0;
                    $totalDiscount = 0;
                    $totalPrice = 0;
                    $totalPayment = 0;
                    $totalDue = 0;
                    $serial = 1;
                @endphp
                @foreach ($purchases as $item)
                    @php
                        $totalQty += $item['total_qty'];
                        $totalMainPrice += $item['main_price'];
                        $totalCommission += $item['commission'];
                        $totalDiscount += $item['discount'];
                        $totalPrice += $item['total_price'];
                        $totalPayment += $item['payment_received'];
                        $totalDue += $item['due_to_company'];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                        <td class="text-center">{{ $item->invoice_no }} </td>
                        <td class="text-center">{{ $item->total_qty }} </td>

                        <td class="text-center">{{ showAmount($item->main_price) }} Tk </td>
                        <td class="text-center">{{ showAmount($item->commission) }} % </td>
                        <td class="text-center">{{ showAmount($item->discount) }} Tk </td>

                        <td class="text-center">{{ showAmount($item->total_price) }} Tk </td>
                        <td class="text-center">{{ showAmount($item->payment_received) }} Tk </td>

                        <td class="text-center">{{ showAmount($item->due_to_company) }} Tk</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-size: 16px;">
                    <td class="fw-bold">Total: </td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"><strong>{{ $totalQty }} </strong></td>
                    <td class="text-center"><strong>{{ $totalMainPrice }} Tk</strong></td>
                    <td class="text-center"><strong>{{ $totalCommission }} %</strong></td>
                    <td class="text-center"><strong>{{ $totalDiscount }} Tk</strong></td>
                    <td class="text-center"><strong>{{ $totalPrice }} Tk</strong></td>
                    <td class="text-center"><strong>{{ $totalPayment }} Tk</strong></td>
                    <td class="text-center"><strong>{{ $totalDue }} Tk</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
