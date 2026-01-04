<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ public_path('uploads/favicon/' . gs()->favicon) }}">
    <title>Quotation</title>
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
            color: #4E5F8A;
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
                <td style="width:100px; vertical-align: top; text-align: right; padding: 0; padding-top:10px;">
                    <img src="{{ public_path('uploads/logo/' . gs()->logo) }}" style="width:100px;" />
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
        <h2 style="text-align: center; text-decoration: underline;">QUOTATION</h2>

        <table class="customer-supplier">
            <tr>
                <td class="supplier-details">
                    <strong>Project Name : </strong><span>{{ $quotation->title }}</span><br>
                    <strong>Project No : </strong><span>{{ $quotation->floor_info }}</span><br>
                    {{-- <strong>TRN : </strong><span>{{ gs()->trn_number }}</span><br> --}}
                    <strong>Sub Contractor Name : </strong><span>{{ gs()->company_name }}</span><br>
                    <strong>Address : </strong><span>{{ gs()->address }}</span><br>
                    <strong>Phone : </strong><span>{{ gs()->primary_contact_number }}</span><br>
                    <strong>Email : </strong><span>{{ gs()->primary_email_address }}</span><br>
                </td>
                <td class="customer-details">
                     <p>Date: {{ showDateTime($quotation->quotation_date) }}</p>
                    <strong>To</strong><br>
                    <strong>Company Name : </strong><span>{{ $quotation->customer->company }}</span><br>
                    <strong>Name No : </strong><span>{{ $quotation->customer->name }}</span><br>
                    <strong>Supplier TRN No : </strong><span>{{ $quotation->customer->trn_number }}</span><br>
                    <strong>TRN date : </strong><span>{{ $quotation->customer->trn_date }}</span><br>
                    <strong>Email : </strong><span>{{ $quotation->customer->email }}</span><br>
                </td>
            </tr>
        </table>


        <!-- Invoice Items -->
        <table class="product-details-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach ($quotation->items as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        {{-- <p class="item_notes">{{@$item->product->name}}</p> --}}
                        <p>{{@$item->product->description}}</p>
                    </td>
                    <td class="center">{{$item->qty}}</td>
                    <td class="center">{{@$item->product->unit->name}}</td>
                    <td class="center">{{showAmount($item->unit_price, 2, false)}}</td>
                    <td class="center">
                        {{showAmount( $item->total, 2, false)}}
                    </td>
                </tr>
                @php
                $total += $item->total;
                @endphp
                @endforeach

               @php
            function convertNumberToWords($num)
            {
            $ones = array(
            0 => "Zero", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four",
            5 => "Five", 6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine",
            10 => "Ten", 11 => "Eleven", 12 => "Twelve", 13 => "Thirteen",
            14 => "Fourteen", 15 => "Fifteen", 16 => "Sixteen",
            17 => "Seventeen", 18 => "Eighteen", 19 => "Nineteen"
            );

            $tens = array(
            2 => "Twenty", 3 => "Thirty", 4 => "Forty",
            5 => "Fifty", 6 => "Sixty", 7 => "Seventy",
            8 => "Eighty", 9 => "Ninety"
            );

            if ($num < 20) {
                return $ones[$num];
                } elseif ($num < 100) {
                return $tens[intval($num / 10)] . (($num % 10) ? " " . $ones[$num % 10] : "" );
                } elseif ($num < 1000) {
                return $ones[intval($num / 100)] . " Hundred" . (($num % 100) ? " " . convertNumberToWords($num % 100) : "" );
                } elseif ($num < 100000) { // Thousand
                return convertNumberToWords(intval($num / 1000)) . " Thousand" . (($num % 1000) ? " " . convertNumberToWords($num % 1000) : "" );
                } elseif ($num < 10000000) { // Lakh
                return convertNumberToWords(intval($num / 100000)) . " Lakh" . (($num % 100000) ? " " . convertNumberToWords($num % 100000) : "" );
                } elseif ($num < 1000000000) { // Crore
                return convertNumberToWords(intval($num / 10000000)) . " Crore" . (($num % 10000000) ? " " . convertNumberToWords($num % 10000000) : "" );
                } else { // Above Crore (Billion, Trillion etc.)
                return convertNumberToWords(intval($num / 1000000000)) . " Billion" . (($num % 1000000000) ? " " . convertNumberToWords($num % 1000000000) : "" );
                }
                }

                // Example use:
                $integerPart=floor($total);
                $decimalPart=round(($total - $integerPart) * 100);
                $grandTotalWords=convertNumberToWords($integerPart);
                if ($decimalPart> 0) {
                $grandTotalWords .= " and " . convertNumberToWords($decimalPart) . " Cents";
                }
                @endphp
            </tbody>
            <tfoot>
                <tr>
                    <td class="center" colspan="3"><strong>{{ $grandTotalWords  }}</strong></td>
                    <td class="center" colspan="2"><strong>Gross Amount:</strong></td>
                    <td class="center">{{showAmount($total, 2, false)}} Tk</td>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <div style="">
            <p><strong>Payment Terms as follows :</strong></p>
            <p><strong>Invoice To Be Submitted Every 15 Days</strong></p>
            <P><strong>Payment To Be Made Within 15 Days From The Date Of Invoice</strong></P>
        </div>
        <div style="margin-top: 10px;">
            <h2 style="color: #2a8df7ff; text-decoration:underline;">{{ gs()->company_name }}</h2>
            <h2 style="color: #2a8df7ff;">{{gs()->owner_name}} :</h2>
            <h3 style="color: #2a8df7ff">OWNER</h3>
        </div>
        
        <!-- Footer -->
         <htmlpagefooter name="myfooter">
        <div class="footer">
            <h3 style="color: #2a8df7ff; text-decoration:underline;">Email : {{ gs()->primary_email_address }}, Mobile : {{ gs()->primary_contact_number }}</h3>
        </div>
        </htmlpagefooter>
    </div>
</body>

</html>
