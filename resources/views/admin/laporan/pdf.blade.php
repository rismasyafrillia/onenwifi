<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #eee; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

<h2 class="text-center">LAPORAN PEMBAYARAN</h2>

<p>
    <b>Periode Laporan:</b><br>

    @if ($bulan && $tahun)
        {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}
    @elseif ($bulan)
        Bulan {{ date('F', mktime(0,0,0,$bulan,1)) }}
    @elseif ($tahun)
        Tahun {{ $tahun }}
    @else
        Semua Bulan dan Tahun
    @endif
</p>

<hr>

<p>
    Total Tagihan: <b>{{ $totalTagihan }}</b><br>
    Lunas: <b>{{ $lunas }}</b><br>
    Menunggak: <b>{{ $menunggak }}</b><br>
    Total Pembayaran: <b>Rp {{ number_format($totalNominal) }}</b>
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Periode</th>
            <th>Nominal</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tagihans as $t)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $t->periode }}</td>
            <td class="text-right">Rp {{ number_format($t->nominal) }}</td>
            <td class="text-center">{{ ucfirst($t->status) }}</td>
            <td class="text-center">{{ $t->created_at->format('d-m-Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<p>
    Dicetak pada: {{ now()->format('d-m-Y H:i') }}
</p>
</body>
</html>
