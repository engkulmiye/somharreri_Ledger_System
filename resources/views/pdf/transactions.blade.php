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

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Total</th>
            <th>Remaining</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->date }}</td>
                <td>{{ $row->partner_display_name }}</td>
                <td>{{ ucfirst($row->type) }}</td>
                <td>${{ number_format($row->total_amount, 2) }}</td>
                <td>${{ number_format($row->remaining_amount, 2) }}</td>
                <td>{{ ucfirst($row->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
