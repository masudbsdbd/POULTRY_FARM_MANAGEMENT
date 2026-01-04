<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Ledger</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 98%;
            margin: 0 auto;
            padding: 5px 0;
        }

        /* Header table for logo and supplier details - removed borders */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            /* Removed border */
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
            border: none;
            /* Removed border */
        }

        .logo-cell {
            width: 30%;
            text-align: left;
        }

        .details-cell {
            width: 70%;
            text-align: right;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .supplier-details {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 4px;
            text-align: left;
        }

        .supplier-details h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .supplier-details p {
            margin: 3px 0;
            font-size: 14px;
            line-height: 1.3;
        }

        .supplier-details .label {
            font-weight: 500;
            color: #666;
            display: inline-block;
            width: 85px;
        }

        .supplier-details .value {
            color: #333;
        }

        .advance {
            margin-top: 5px;
            padding-top: 5px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .title {
            text-align: center;
            flex-grow: 1;
            font-size: 24px;
            font-weight: bold;
        }

        .date-range {
            text-align: center;
            margin-bottom: 10px;
        }

        .date-box {
            border: 1px dashed #999;
            padding: 6px 20px;
            display: inline-block;
            border-radius: 4px;
        }

        /* Data table styles */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #999;
            padding: 4px 6px;
            text-align: left;
        }

        .section-header {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .total-row {
            background-color: #f9f9f9;
        }

        .total-cell {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header table with logo and supplier details - no borders -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('uploads/logo/' . gs()->logo) }}" alt="Logo" class="logo"
                        width="100">
                    <p style="margin: 0;">{{ gs()->company_name }}</p>
                </td>
            </tr>
        </table>

        <div>
            <h1 class="title">Accounts Payable</h1>
        </div>

        @if (isset($givenDate))
            <div class="date-range">
                <div class="date-box">
                    @if (is_array($givenDate))
                        {{ showDateTime($givenDate[0], false) }} To {{ showDateTime($givenDate[1], false) }}
                    @else
                        {{ showDateTime($givenDate, false) }}
                    @endif
                </div>
            </div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Purchase Batch</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Employee</th>
                    <th class="text-center">Account's Head</th>
                    <th class="text-center">Due Amount (Tk)</th>
                    <th class="text-center">Customer Advance Amount (Tk)</th>
                    <th class="text-center">Payable Amount (Tk)</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $totalDueAmount = 0;
                    $totalAdvanceAmount = 0;
                @endphp
                @foreach ($payables as $item)
                    @php
                        $totalDueAmount += $item->due_amount;
                        $totalAdvanceAmount += $item->customer_advance_amount;
                    @endphp
                    <tr>
                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                        <td class="text-center">
                            @if (isset($item->supplier_id))
                                {{ $item->purchase->batch->batch_code }}
                            @else
                                ---
                            @endif
                        </td>
                        <td class="text-center">
                            @if (isset($item->supplier_id))
                                {{ $item->supplier->name }}
                            @else
                                ---
                            @endif
                        </td>
                        <td class="text-center">
                            @if (isset($item->customer_id))
                                {{ $item->customer->name }}
                                ({{ $item->customer->code }})
                            @else
                                ---
                            @endif
                        </td>
                        <td class="text-center">
                            @if (isset($item->employee_id))
                                {{ $item->employee->name }}
                                ({{ $item->employee->code }})
                            @else
                                ---
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-secondary">{{ $item->payablesHead->name }}</span>
                        </td>
<<<<<<< HEAD
                        <td class="text-center">{{ showAmount($item->due_amount) }} Tk</td>
                        <td class="text-center">{{ showAmount($item->customer_advance_amount) }} Tk</td>
                        <td class="text-center">{{ showAmount($item->payable_amount) }} Tk</td>
=======
                        <td class="text-center">{{ showAmount($item->due_amount) }} </td>
                        <td class="text-center">{{ showAmount($item->customer_advance_amount) }} </td>
                        <td class="text-center">{{ showAmount($item->payable_amount) }} </td>
>>>>>>> origin/shakir
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
