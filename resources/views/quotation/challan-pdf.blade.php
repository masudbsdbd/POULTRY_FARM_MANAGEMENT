<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ public_path('uploads/favicon/' . gs()->favicon) }}">
    <title></title>
    <style>
        @page {
            margin: 50px 50px 110px 50px;
            footer: myfooter;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
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

        .invoice-image {
            width: 100%;
            height: auto;
        }

        /* Remove border from customer-supplier table */
        .customer-supplier td,
        .customer-supplier th {
            border: none;
        }


        .header .details {
            text-align: center;
        }

        .invoice-header {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            color: #0a6319ff;
        }

        .customer-supplier td {
            vertical-align: top;
        }

        .customer-supplier .supplier-details {
            width: 48%;
        }

        .customer-supplier .customer-details {
            width: 30%;
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
        .product-details-table .center,
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

        .item_notes {
            font-weight: bold;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <p style="text-align: right; font-size:10px;color:#666">print Date : {{ Date::now() }}</p>
         <table class="header" style="width: 100%; border-collapse: collapse; border-spacing: 0;">
            <tr>
                <td style="width:80px; vertical-align: top; text-align: right; padding: 0; padding-top:10px">
                    <img src="{{ public_path('uploads/logo/' . gs()->logo) }}" style="width:80px;" />
                </td>
                
                <td class="details" style="vertical-align: top; text-align: center; padding: 0;">
                    <h1 class="invoice-header">{{ gs()->company_name }}</h1>
                    <p>{{ gs()->address }} Mobile: {{ gs()->primary_contact_number }}</p>
                    <p>Email: {{ gs()->primary_email_address }}</p>
                </td>
                <td style="vertical-align: top; text-align: right; padding: 0;">
                    
                </td>
            </tr>
        </table>
        <h3 style="text-align: center;">Working Done : {{$challan->challan_number }}</h2>

        <!-- Supplier and Customer Details -->
        <table class="customer-supplier">
            <tr>
                <td class="supplier-details">
                    <strong>Project Name : </strong><span>42342</span><br>
                    <strong>Project No : </strong><span>123</span><br>
                    <strong>TRN : </strong><span>{{ gs()->trn_number }}</span><br>
                    <strong>Sub Contractor Name : </strong><span>{{ gs()->company_name }}</span><br>
                    <strong>Address : </strong><span>{{ gs()->address }}</span><br>
                    <strong>Phone : </strong><span>{{ gs()->primary_contact_number }}</span><br>
                    <strong>Email : </strong><span>{{ gs()->primary_email_address }}</span><br>
                    <strong>Supplier TRN No : </strong><span>{{$quotationsInfo->customer->trn_number}}</span>
                    <strong>TRN date : </strong><span>{{$quotationsInfo->customer->trn_date}}</span><br>
                </td>
                <td class="customer-details">
                    <p>Date: {{ showDateTime($challan->challan_date) }}</p>
                </td>
            </tr>
        </table>

        <table class="product-details-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Unit</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>
                @php
                $grandTotal = 0;
                @endphp
                @foreach ($challanItems as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        <p>{{$item->product->name}}</p>
                        <p style="padding-top: 20px; font-weight: bold;"> {{ $item->floor->building->name }} : {{ $item->floor->name }}</p>

                    </td>
                    <td class="center">{{$item->quantity}}</td>
                    <td class="center">{{ showAmount($item->unit_price, 2, false) }}</td>
                    <td class="center">{{$item->product->unit->name}}</td>
                    <td class="center">
                        @php
                        $grandTotal += $item->total;
                        @endphp
                        {{showAmount( $item->total, 2, false)}}
                    </td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td class="center" colspan="5">
                        <strong>Gross Amount(AED)</strong>
                    </td>
                    <td class="center">{{showAmount( $grandTotal, 2, false)}}</td>
                </tr>
            </tfoot>
        </table>

        <!-- <htmlpagefooter name="myfooter">
            <table width="100%" style="border:none; font-size:12px;">
                <tr>
                    <td width="50%" style="text-align:left; padding-left:40px; border:none;">
                        <span>________________________</span><br><br>
                        <strong>Approved by Manager</strong>
                    </td>
                    <td width="50%" style="text-align:right; padding-right:40px; border:none;">
                        <span>________________________</span><br><br>
                        <strong>Approved by Engineer</strong>
                    </td>
                </tr>
            </table>
        </htmlpagefooter> -->
    </div>
</body>

</html>