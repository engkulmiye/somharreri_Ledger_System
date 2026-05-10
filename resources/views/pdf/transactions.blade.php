<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
        }
         .header {
        width: 100%;
        margin-bottom: 20px;
        border-bottom: 2px solid #2563eb;
        padding-bottom: 10px;
    }

     .header h2 {
        margin: 0;
        color: #2563eb;
        font-size: 22px;
      }

      .meta {
        margin-top: 5px;
        font-size: 12px;
    }

    .meta strong {
        color: #111827;
    }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #1e3a8a;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }
        tr:nth-child(even) {
            background: #f1f5f9;
        }
    </style>
</head>
<body>



<div class="header">
        <h2>Company Transactions Report</h2>
        <div class="meta">
            <strong>From:</strong> Galaal Hashi <br>
            <strong>Date:</strong> {{ now()->format('d M Y') }}
        </div>
    </div>

@php
    $totalDebt = $transactions->where('type', 'debt')->sum('total_amount');
    $totalPayment = $transactions->where('type', 'payment')->sum('total_amount');
    $runningBalance = $totalDebt - $totalPayment;
@endphp

<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; font-size: 12px;">
    <thead>
        <tr style="background:#111827;color:white;">
            <th>Date</th>
            <th>Name</th>
            <th>Type</th>
            <th>Amount USD</th>
            <th>Commission</th>
            <th>Total Amount</th>


        </tr>
    </thead>

    <tbody>
        @foreach ($transactions as $tx)
        <tr style="border-bottom:1px solid #ddd;">
            <td>{{ $tx->date }}</td>

            <td style="
                color: {{ $tx->manual_partner_name ? '#dc2626' : '#000' }};
                font-weight: {{ $tx->manual_partner_name ? 'bold' : 'normal' }};
            ">
                {{ $tx->partner_display_name }}
            </td>

            <td>{{ ucfirst($tx->type) }}</td>

            <td style="text-align:right">${{ number_format($tx->amount_usd, 2) }}</td>
                        <td style="text-align:right">${{ number_format($tx->commission_amount, 2) }}</td>
            <td style="text-align:right">${{ number_format($tx->total_amount, 2) }}</td>


        </tr>
        @endforeach



      <tr style="font-weight:bold;background:#1e3a8a; color:white;">
    <td colspan="5" style="text-align:right;">Wadarta Deyn</td>
    <td colspan="1" style="text-align:right;">
        ${{ number_format($totalDebt, 2) }}
    </td>
</tr>

<tr style="font-weight:bold;background:#59e58a; color:white;">
    <td colspan="5" style="text-align:right;">Wadarta La Bixiyay</td>
    <td colspan="1" style="text-align:right;">
        ${{ number_format($totalPayment, 2) }}
    </td>
</tr>

<tr style="font-weight:bold;background:#d34c4c; color:white;">
    <td colspan="5" style="text-align:right;">Resto Balance</td>
    <td colspan="1" style="text-align:right;">
        ${{ number_format($runningBalance, 2) }}
    </td>
</tr>
    </tbody>
</table>

</body>
</html>
