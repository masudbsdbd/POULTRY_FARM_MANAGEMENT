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
            <h1 class="title">Account Ledger</h1>
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
                    <th class="text-center">Invoice Number</th>
                    <th class="text-center">Debit</th>
                    <th class="text-center">Credit</th>
                    <th class="text-center">Balance</th>

                </tr>
            </thead>
            <tbody>
            @php
                $totalDebit = 0;
                $totalCredit = 0;
                $totalDiscount = 0;
                $balance = 0;
                $updatedCredit = 0;
                $updatedDebit = 0;
                $serial = 1;
            @endphp
                @foreach ($supplierAccData as $item)
                @php
                    $debit = $item->type == 11 ? $item->credit : $item->debit;
                    $credit = $item->type == 6 ? $item->credit : $item->amount;

                    $totalDebit += $debit;
                    $totalCredit += $credit;
                    $updatedCredit += $credit;   
                    $updatedDebit += $debit;   

                @endphp
                    <tr>
                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                        <td class="text-center">{{ $item->purchase->invoice_no ?? 'N/A' }} </td>
                        <td class="text-center">{{ showAmount($debit) }} </td>
                        <td class="text-center">{{ showAmount($credit) }} </td>
                        <td class="text-center">{{ showAmount($updatedDebit - $updatedCredit ) }} </td>

                    </tr>
                @endforeach
                <tr style="font-size: 16px;">
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"><strong>{{ $totalDebit }} Tk</strong></td>
                    <td class="text-center"><strong>{{ $totalCredit }} Tk</strong></td>
                    <td class="text-center"></td>

                </tr>
                <tr style="font-size: 16px;">
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center">Closing Balance: </td>
                    <td class="text-center">
                        <strong>
                            @if ($totalDebit - $totalCredit > 0)
                                Advance: {{ $totalDebit - $totalCredit }} Tk
                            @elseif ($totalDebit - $totalCredit < 0)
                                Due: {{ abs($totalDebit - $totalCredit) }} Tk
                            @else
                                --
                            @endif
                        </strong>
                    </td>
                    <td class="text-center"></td>

                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
