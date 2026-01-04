<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Balance Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f4f4f4;
            font-size: 12px;
        }
        .container {
            max-width: 1000px;
            background: white;
            padding: 15px;
            margin: auto;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .section {
            margin-top: 10px;
        }
        .section-title {
            background: #888;
            color: white;
            padding: 3px 6px;
            font-weight: bold;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 11px;
        }
        th, td {
            padding: 5px 6px;
            border: none; /* No borders */
        }
        th {
            background: #eee;
        }
        .total, .highlight {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .highlight {
            background-color: #f1f1f1;
        }
        /* New classes for alignment */
        td.description, th.description {
            text-align: left;
        }
        td.amount, th.amount {
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Balance Sheet</h2>
        <p class="header">As of {{ request('date') }}</p>
        
        <div class="section">
            <div class="section-title">Assets</div>
            <table>
                <thead>
                    <tr>
                        <th class="description">Current Assets</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="description">Cash</td>
                        <td class="amount">{{ showAmount($currentCashAmount) }}</td>
                    </tr>
                    <tr>
                        <td class="description">Bank</td>
                        <td class="amount">{{ showAmount($currentBankAmount) }}</td>
                    </tr>
                    <tr>
                        <td class="description">Inventory</td>
                        <td class="amount">{{ showAmount($totalPurchaseStock) }}</td>
                    </tr>
                    <tr>
                    <td class="description">Advance Salary</td>
                    <td class="amount">{{ showAmount($empAdvanceSalary) }}</td>
                    </tr>

                    <tr class="highlight">
                        <td class="description">Total Current Assets</td>
                        <td class="amount">{{ showAmount($currentCashAmount + $currentBankAmount + $totalPurchaseStock + $totalReceivableAmount + $empAdvanceSalary) }}</td>
                    </tr>

                    <tr><td colspan="2"><strong>Accounts Receivable</strong></td></tr>
                    @foreach($totalReceivableAmountSeparator as $item)
                    <tr>
                        <td class="description">{{ $item->receivable_head_name }}</td>
                        <td class="amount">{{ showAmount($item->total_amount + $item->effective_amount) }} </td>
                    </tr>
                    @endforeach
                    <tr class="highlight">
                        <td class="description">Total Accounts Receivable</td>
                        <td class="amount">{{ showAmount($totalReceivableAmount) }} </td>
                    </tr>

                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th class="description">Long-term Assets</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($totalAsstAmountSeparator as $item)
                    <tr>
                        <td class="description">{{ $item->asset_head_name }}</td>
                        <td class="amount">{{ showAmount($item->total_amount + $item->effective_amount) }} </td>
                    </tr>
                    @endforeach

                    <tr class="highlight">
                        <td class="description">Total Long-term Assets</td>
                        <td class="amount">{{ showAmount($totalAssets) }} </td>
                    </tr>

                    <tr class="highlight">
                        <td class="description">Total Assets</td>
                        <td class="amount">{{ showAmount($totalAssets + $currentCashAmount + $currentBankAmount + $totalReceivableAmount + ($totalPurchaseStock) + $empAdvanceSalary) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Liabilities</div>
            <table>
                <thead>
                    <tr>
                        <th class="description">Liabilities</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($totalPayableAmountSeparator as $item)
                    <tr>
                        <td class="description">{{ $item->payables_head_name }}</td>
                        <td class="amount">{{ showAmount($item->total_amount + $item->effective_amount) }} </td>
                    </tr>
                    @endforeach
                    <tr class="highlight">
                        <td class="description">Total Liabilities</td>
                        <td class="amount">{{ showAmount($totalPayableAmount) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Equity</div>
            <table>
                <thead>
                    <tr>
                        <th class="description">Equity</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="description">Owner's Capital</td>
                        <td class="amount">
                            {{ 
                                showAmount(
                                    (
                                        + $totalOwnerAmount
                                        - $totalDamageAmount
                                        + $totalPurchaseIncreaseAmount
                                    )
                                ) 
                            }}

                        </td>
                    </tr>
                    <tr>
                        <td class="description">Other's Earnings</td>
                        <td class="amount">{{ showAmount(($incomes + $totalSellProfit + $totalEffectiveIncomeAmount) - $totalExpenseAmount) }}</td>
                    </tr>
                    <tr class="highlight">
                        <td class="description">Liabilities & Equity Total</td>
                        <td class="amount">
                                {{ 
                                showAmount
                                    (
                                    ($totalPayableAmount)
                                    +
                                    (
                                        + $totalOwnerAmount
                                        - $totalDamageAmount
                                        + $totalPurchaseIncreaseAmount
                                    )
                                    + 
                                    (
                                        ($incomes + $totalSellProfit + $totalEffectiveIncomeAmount) - $totalExpenseAmount
                                    )
                                    )
                                }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
