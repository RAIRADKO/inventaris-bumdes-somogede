<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Neraca</title>
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
        .section-header {
            background-color: #e0e0e0;
            font-weight: bold;
            font-size: 13px;
        }
        .total-row {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .text-right {
            text-align: right;
        }
        .grand-total {
            background-color: #333;
            color: white;
            font-size: 13px;
        }
        .asset-header { background-color: #cce5ff; }
        .liability-header { background-color: #f8d7da; }
        .equity-header { background-color: #e2d5f7; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMDES SOMOGEDE</h1>
        <p>Laporan Neraca</p>
        <p>Per Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    <table>
        <tr class="section-header asset-header">
            <td colspan="3">ASET</td>
        </tr>
        @foreach($assets as $account)
        <tr>
            <td width="15%">{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td width="25%" class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">Total Aset</td>
            <td class="text-right">Rp {{ number_format($totalAssets, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>

        <tr class="section-header liability-header">
            <td colspan="3">KEWAJIBAN</td>
        </tr>
        @foreach($liabilities as $account)
        <tr>
            <td>{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">Total Kewajiban</td>
            <td class="text-right">Rp {{ number_format($totalLiabilities, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>

        <tr class="section-header equity-header">
            <td colspan="3">EKUITAS</td>
        </tr>
        @foreach($equities as $account)
        <tr>
            <td>{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">Total Ekuitas</td>
            <td class="text-right">Rp {{ number_format($totalEquity, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>

        <tr class="grand-total">
            <td colspan="2"><strong>KEWAJIBAN + EKUITAS</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <p style="margin-top: 30px; font-size: 10px; color: #999;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>
</body>
</html>
