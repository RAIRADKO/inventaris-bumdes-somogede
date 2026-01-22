<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neraca Saldo</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #333;
            color: white;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMDES SOMOGEDE</h1>
        <p>Neraca Saldo (Trial Balance)</p>
        <p>Per Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Kode</th>
                <th>Nama Akun</th>
                <th width="20%" class="text-right">Debit</th>
                <th width="20%" class="text-right">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ $account->code }}</td>
                <td>{{ $account->name }}</td>
                <td class="text-right">{{ $account->debit_balance > 0 ? 'Rp ' . number_format($account->debit_balance, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $account->credit_balance > 0 ? 'Rp ' . number_format($account->credit_balance, 0, ',', '.') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalDebit, 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalCredit, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @php
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01;
    @endphp

    <p style="margin-top: 20px; padding: 10px; background-color: {{ $isBalanced ? '#d4edda' : '#f8d7da' }}; border-radius: 5px; text-align: center;">
        @if($isBalanced)
            ✓ Neraca Saldo Seimbang (Balance)
        @else
            ✗ Neraca Saldo Tidak Seimbang - Selisih: Rp {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
        @endif
    </p>

    <p style="margin-top: 30px; font-size: 10px; color: #999;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>
</body>
</html>
