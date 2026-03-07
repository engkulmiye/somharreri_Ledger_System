<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #1f2937;
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

    .invoice-box {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    thead {
        background: #2563eb;
        color: white;
    }

    th {
        padding: 8px;
        text-align: left;
        font-size: 11px;
        text-align: center;
        text-transform: uppercase;
    }

    td {
        padding: 6px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 11px;
        text-align: center;
    }

    tr:nth-child(even) {
        background: #f8fafc;
    }

    .badge-debt {
        background: #fee2e2;
        color: #991b1b;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
    }

    .badge-payment {
        background: #dcfce7;
        color: #166534;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
    }

    .totals {
        margin-top: 15px;
        width: 100%;
    }

    .totals td {
        border: none;
        font-size: 12px;
        padding: 4px;
    }

    .totals .label {
        text-align: right;
        font-weight: bold;
        color: #374151;
    }

    .totals .value {
        text-align: right;
        font-weight: bold;
        color: #111827;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #6b7280;
        border-top: 1px dashed #d1d5db;
        padding-top: 8px;
    }
</style>

<div class="invoice-box">

    <div class="header">
        <h2>Customer Statement</h2>
        <div class="meta">
            <strong>Customer:</strong> {{ $customer->name }} <br>
            <strong>Date:</strong> {{ now()->format('d M Y') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Type</th>
                <th>Total</th>
                <th>Commission</th>
                <th>Resto</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
                $totalCommission = 0;
                $totalRemaining = 0;
            @endphp

            @foreach ($transactions as $row)
                 @php
                    $totalAmount += $row->total_amount;
                    $totalCommission += $row->commission_amount;
                    $totalRemaining += $row->remaining_amount;
                @endphp

                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                    <td>{{ $row->manual_partner_name }}</td>
                    <td>
                        <span class="{{ $row->type === 'debt' ? 'badge-debt' : 'badge-payment' }}">
                            {{ ucfirst($row->type) }}
                        </span>
                    </td>
                    <td>${{ number_format($row->total_amount, 2) }}</td>
                    <td>${{ number_format($row->commission_amount, 2) }}</td>
                    <td>${{ number_format($row->remaining_amount, 2) }}</td>
                </tr>
            @endforeach
            <tr style="font-weight:bold;background:#f3f4f6;">
    <td colspan="4" style="text-align:right;">TOTAL</td>

    <td style="text-align:center;">
        ${{ number_format($transactions->sum('commission_amount'), 2) }}
    </td>

    <td style="text-align:center;">
        ${{ number_format($transactions->sum('remaining_amount'), 2) }}
    </td>
   </tr>
        </tbody>
    </table>

</div>

