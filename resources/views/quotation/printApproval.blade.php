<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ public_path('uploads/favicon/' . gs()->favicon) }}">
    <title>Approval</title>
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
        .page-title{
            text-align: center;
            color: #000000ff;
            font-size: 15px;
            width: 100%;
            background-color: #ddddddff;
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
        <p style="text-align: right; font-size:10px;color:#666">print Date : {{ Date::now() }}</p>
        <table class="header" style="width: 100%; border-collapse: collapse; border-spacing: 0;">
            <tr>
                <td style="width:80px; vertical-align: top; text-align: right; padding: 0; padding-top:10px">
                    <img src="{{ public_path('uploads/logo/' . gs()->logo) }}" style="width:100px;" />
                </td>
                
                <td class="details" style="vertical-align: top; text-align: center; padding: 0;">
                    <h1 class="invoice-header">{{ gs()->company_name }}</h1>
                    <p>{{ gs()->address }} Mobile: {{ gs()->primary_contact_number }}</p>
                    <p>Email: {{ gs()->primary_email_address }}</p>
                    <p>TRN: {{ gs()->trn_number }}</p>
                    <p>Date: {{ showDateTime($quotation->quotation_date) }}</p>
                </td>
                <td style="vertical-align: top; text-align: right; padding: 0;">
                    
                </td>
            </tr>
        </table>
        <div class="keep-together" style="text-align: center; margin-top: 10px;">
            @if(!empty($approvalInfo->diagram_image))
            <img src="{{ public_path('uploads/approval/' . $approvalInfo->diagram_image) }}"
                alt="diagram"
                style="margin-bottom: 30px; width:100%; height:600px;" />
            @endif
        </div>
            <table class="product-details-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organizedItems as $productId => $item)
                        <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            {{-- <p class="item_notes">{{ $item->product->name }}</p> --}}
                            <p>{{ $item["product_name"] }}</p>
                            <p style="font-weight: bold;">
                                @foreach ($item["building"] as $building => $floors)
                                    {{ $building }}: {{ $floors }}<br>
                                @endforeach
                            </p>
                        </td>
                        <td class="center">{{ $item["unit_name"] }}</td>
                        <td class="center">{{ $item["total_approved"] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
        @if (!empty($approvalInfo->notes))
            <p style="margin-top: 20px;">Note: {{ $approvalInfo->notes }}</p>
        @endif
        <!-- Footer -->
        
         <htmlpagefooter name="myfooter">
            <table width="100%" style="border:none; font-size:12px;">
                <tr>
                    <td width="50%" style="text-align:left; padding-left:40px; border:none;">
                        <span>________________________</span><br><br>
                        <strong>Approved by Engineer</strong>
                    </td>
                    <td width="50%" style="text-align:right; padding-right:40px; border:none;">
                        <span>________________________</span><br><br>
                        <strong>Approved by Foreman</strong>
                    </td>
                </tr>
            </table>
        </htmlpagefooter>
          
    </div>
</body>

</html>