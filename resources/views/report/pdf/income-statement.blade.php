<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
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
        .net-income {
            background-color: #333;
            color: white;
            font-size: 14px;
        }
        .positive { color: #28a745; }
        .negative { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMDES SOMOGEDE</h1>
        <p>Laporan Laba Rugi</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <table>
        <tr class="section-header">
            <td colspan="3">PENDAPATAN</td>
        </tr>
        @foreach($revenues as $account)
        <tr>
            <td width="15%">{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td width="25%" class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">Total Pendapatan</td>
            <td class="text-right positive">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>

        <tr class="section-header">
            <td colspan="3">BEBAN</td>
        </tr>
        @foreach($expenses as $account)
        <tr>
            <td>{{ $account->code }}</td>
            <td>{{ $account->name }}</td>
            <td class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">Total Beban</td>
            <td class="text-right negative">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>

        <tr class="net-income">
            <td colspan="2"><strong>LABA/RUGI BERSIH</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($netIncome, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <p style="margin-top: 30px; font-size: 10px; color: #999;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>
</body>
</html>
