<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Ledger</title>
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

        <div>
            <h1 class="title">Total Profit/Loss Info</h1>
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
                    <th>Purchase Batch</th>
                    <th>Product</th>
                    <th>Purchase Price</th>
                    <th>Sell Price</th>
                    <th>Discount</th>
                    <th>Sell Qty</th>
                    <th>Total Purchase Price (after sell)</th>
                    <th>Total Sell Price (after sell)</th>
                    <th>Profit/Loss</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $totalSellQty = 0;
                    $totalPurchasePrice = 0;
                    $totalSellPrice = 0;
                    $totalProfit = 0;
                    $serial = 1;
                @endphp
                @foreach ($sellRecords as $item)
                    @php
                        $totalSellQty += $item['sell_qty'];
                        $totalPurchasePrice += $item['avg_purchase_price'] * $item['sell_qty'];
                        $totalSellPrice += $item['avg_sell_price'] * $item['sell_qty'];
                        $totalProfit += ($item['avg_sell_price'] * $item['sell_qty']) - ($item['avg_purchase_price'] * $item['sell_qty']);
                    @endphp
                    <tr>
                        <td>{{ showDateTime($item->created_at) }}</td>
                        <td>{{ $item->purchaseBatch->batch_code }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ showAmount($item->avg_purchase_price) }} </td>
                        <td>{{ showAmount($item->avg_sell_price) }} </td>
                        <td>{{ showAmount($item->discount) }} %</td>
                        <!-- <td>{{ $item->sell_qty }} {{ $item->product->unit->name }}</td> -->
                        <td>{{ $item->sell_qty }} </td>
                        <td>{{ number_format($item->avg_purchase_price * $item->sell_qty, 2) }} </td>
                        <td>{{ number_format($item->avg_sell_price * $item->sell_qty, 2) }} </td>
                        <td>{{ number_format(($item->avg_sell_price * $item->sell_qty) - ($item->avg_purchase_price * $item->sell_qty), 2) }} </td>

                    </tr>
                @endforeach
                <tr class="total-row" style="font-size: 16px;">
                    <td class="fw-bold">Total: </td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong>{{ $totalSellQty }}</strong></td>
                    <td class="text-center"><strong> {{ showAmount($totalPurchasePrice) }} Tk</strong></td>
                    <td class="text-center"><strong>{{showAmount($totalSellPrice)}} Tk</strong></td>
                    <td class="text-center"><strong> {{ showAmount($totalProfit) }} Tk</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
