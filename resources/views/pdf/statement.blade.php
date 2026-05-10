<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Financial Statement</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
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
            line-height: 1.7;
        }

        .summary {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 20px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 12px;
        }

        .summary-row {
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #111827;
            color: white;
            padding: 8px;
            border: 1px solid #d1d5db;
            text-align: center;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f3f4f6;
        }

        .debt {
            color: #15803d;
            font-weight: bold;
        }

        .payment {
            color: #dc2626;
            font-weight: bold;
        }

        .footer-box {
            margin-top: 25px;
            background: #f3f4f6;
            padding: 12px;
            border: 1px solid #d1d5db;
        }

        .footer-box h3 {
            margin: 0 0 10px 0;
            color: #111827;
        }

        .signature {
            margin-top: 40px;
            font-size: 11px;
            text-align: right;
            color: #6b7280;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">

        <h2>Warbixinta Xisaabaadka Maaliyadeed</h2>

        <div class="meta">

            <strong>From:</strong>  Galaal Hashi


            <strong>Warbixinta Ilaa:</strong>
            {{ \Carbon\Carbon::parse($currentTransaction->date)->format('d M Y') }}

            <br>

            <strong>Tixraaca Invoice ka:</strong>
            #{{ $currentTransaction->id }}

            <br>

            <strong>La Soo Saaray:</strong>
            {{ now()->format('d M Y h:i A') }}

        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="summary">

        <div class="summary-row">
            <strong>Wadarta Invoices ka:</strong>
            {{ $transactions->count() }}
        </div>

        <div class="summary-row">
            <strong>Wadarta Deynta:</strong>

            ${{ number_format(
                $transactions
                    ->where('type', 'debt')
                    ->sum('total_amount'),
                2
            ) }}
        </div>

        <div class="summary-row">
            <strong>Wadarta Lacag-bixinnada:</strong>

            ${{ number_format(
                $transactions
                    ->whereIn('type', ['payment', 'company_paid'])
                    ->sum('total_amount'),
                2
            ) }}
        </div>

        <div class="summary-row">
            <strong>Haraaga Xiritaanka:</strong>

            ${{ number_format($closingBalance, 2) }}
        </div>

    </div>

    {{-- TABLE --}}
    <table>

        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Previous Balance</th>
                <th>Running Balance</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($transactions as $tx)

                <tr>

                    {{-- DATE --}}
                    <td>
                        {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                    </td>

                    {{-- PARTNER --}}
                    <td>

                        {{ $tx->partner_display_name }}

                    </td>

                    {{-- TYPE --}}
                    <td>

                        {{ ucfirst($tx->type) }}

                    </td>

                    {{-- AMOUNT --}}
                    <td
                        class="{{ $tx->type === 'debt' ? 'debt' : 'payment' }}">

                        {{ $tx->type === 'debt' ? '+' : '-' }}

                        ${{ number_format($tx->total_amount, 2) }}

                    </td>

                    {{-- PREVIOUS BALANCE --}}
                    <td>

                        ${{ number_format($tx->previous_balance, 2) }}

                    </td>

                    {{-- RUNNING BALANCE --}}
                    <td>

                        ${{ number_format($tx->running_balance, 2) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    {{-- FOOTER SUMMARY --}}
    <div class="footer-box">

        <h3>Warbixin Kooban</h3>

        <p>
            Warbixintani waxay muujinaysaa dhammaan dhaqdhaqaaqyada maaliyadeed laga bilaabo bilowga taariikhda akoonka ilaa Invoice ka lambarkiisu yahay
            #{{ $currentTransaction->id }}.
        </p>

        <p>
            Haraaga ugu dambeeya ee la xisaabiyey:
            <strong>
                ${{ number_format($closingBalance, 2) }}
            </strong>
        </p>

    </div>

    {{-- SIGNATURE --}}
    <div class="signature">

        Generated by Financial GXG Management System

        <br>

        Built by Eng Moha Kulmiye

    </div>

</body>

</html>
