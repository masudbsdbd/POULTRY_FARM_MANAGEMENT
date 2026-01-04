<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Ledger</title>
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
                <td class="details-cell">
                    <div class="supplier-details">
                        <h3>Supplier Details</h3>
                        <p><span class="value">{{ !empty($supplierData->name) ? $supplierData->name : '-' }}
                                ({{ !empty($supplierData->code) ? $supplierData->code : '-' }})</span></p>
                        <p><span
                                class="value">{{ !empty($supplierData->company) ? $supplierData->company : '-' }}</span>
                        </p>
                        <p><span class="value">{{ !empty($supplierData->mobile) ? $supplierData->mobile : '-' }}</span>
                        </p>
                        <p><span
                                class="value">{{ !empty($supplierData->address) ? $supplierData->address : '-' }}</span>
                        </p>
                        <p><span class="value">{{ !empty($supplierData->email) ? $supplierData->email : '-' }}</span>
                        </p>
                        <p class="advance"><span class="label">Advance:</span> <span
                                class="value">{{ !empty(showAmount($supplierData->advance)) ? showAmount($supplierData->advance) : '-' }}</span>
                        </p>
                    </div>
                </td>

            </tr>
        </table>

        <div>
            <h1 class="title">Purchase Ledger</h1>
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
                    <th>Date</th>
                    <th>Qty</th>
                    <th>Main Price</th>
                    <th>Commission(%)</th>
                    <th>Discount</th>
                    <th>Total Price</th>
                    <th>Payment</th>
                    <th>Due</th>
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
                        <td>{{ showDateTime($item->created_at) }}</td>
                        <td>{{ $item->total_qty }} </td>
                        <td>{{ showAmount($item->main_price) }}  </td>
                        <td>{{ showAmount($item->commission) }}  </td>
                        <td>{{ showAmount($item->discount) }}  </td>

                        <td>{{ showAmount($item->total_price) }}  </td>
                        <td>{{ showAmount($item->payment_received) }}  </td>

                        <td>{{ showAmount($item->due_to_company) }} </td>
                    </tr>
                @endforeach
                <tr class="total-row" style="font-size: 16px;">
                    <td class="fw-bold">Total: </td>
                    <td class="text-center"><strong>{{ $totalQty }} </strong></td>
                    <td class="text-center"><strong>{{ showAmount($totalMainPrice) }} Tk</strong></td>
                    <td class="text-center"><strong>{{ showAmount($totalCommission) }} %</strong></td>
                    <td class="text-center"><strong>{{ showAmount($totalDiscount) }} Tk</strong></td>
                    <td class="text-center"><strong>{{ showAmount($totalPrice) }} Tk</strong></td>
                    <td class="text-center"><strong>{{ showAmount($totalPayment) }} Tk</strong></td>
                    <td class="text-center"><strong>{{ showAmount($totalDue) }} Tk</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
