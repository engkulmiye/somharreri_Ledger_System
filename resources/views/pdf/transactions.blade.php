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

<div class="title">TRANSACTIONS REPORT</div>
<div class="subtitle">Generated on {{ now()->format('d M Y H:i') }}</div>
<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; font-size: 12px;">
    <thead>
        <tr style="background:#111827;color:white;">
            <th>Date</th>
            <th>Partner</th>
            <th>Type</th>
            <th>Total Amount</th>
            <th>Commission</th>
            <th>Resto</th>
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

            <td style="text-align:right">${{ number_format($tx->total_amount, 2) }}</td>
            <td style="text-align:right">${{ number_format($tx->commission_amount, 2) }}</td>
            <td style="text-align:right">${{ number_format($tx->remaining_amount, 2) }}</td>
        </tr>
        @endforeach

        <tr style="font-weight:bold;background:#f3f4f6;">
            <td colspan="4" style="text-align:right;">TOTAL</td>
            <td style="text-align:right;">
                ${{ number_format($transactions->sum('commission_amount'), 2) }}
            </td>
            <td style="text-align:right;">
                ${{ number_format($transactions->sum('remaining_amount'), 2) }}
            </td>
            <td></td>
        </tr>
    </tbody>
</table>

</body>
</html>
