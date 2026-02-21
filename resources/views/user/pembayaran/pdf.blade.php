<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body {
    font-family: sans-serif;
    font-size: 13px;
    margin: 0;
}

.box {
    border: 1px solid #ccc;
    padding: 30px;
    position: relative;
}

.header {
    text-align: center;
    margin-bottom: 25px;
}

.header h3 {
    margin: 0;
    font-size: 20px;
}

.header p {
    margin: 3px 0 0;
    font-size: 12px;
    color: #555;
}

.title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
}

table {
    width: 100%;
    margin-top: 10px;
}

td {
    padding: 6px 0;
    vertical-align: top;
}

.total {
    font-size: 16px;
    font-weight: bold;
    margin-top: 15px;
}

.footer {
    margin-top: 30px;
    font-size: 11px;
    color: #555;
}

/* WATERMARK TENGAH PRESISI */
.watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 90px;
    font-weight: bold;
    color: rgba(0, 150, 0, 0.08);
    letter-spacing: 10px;
    white-space: nowrap;
    z-index: 0;
}

/* Supaya konten di atas watermark */
.box > *:not(.watermark) {
    position: relative;
    z-index: 1;
}
</style>
</head>
<body>

<div class="box">

    <div class="header">
        <h3>OneN WiFi</h3>
        <p>Internet Service Provider</p>
    </div>

    <div class="title">INVOICE PEMBAYARAN</div>

    <table>
        <tr>
            <td width="30%"><strong>No Invoice</strong></td>
            <td>: {{ $invoice }}</td>
        </tr>
        <tr>
            <td><strong>Order ID</strong></td>
            <td>: {{ $pembayaran->order_id }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Bayar</strong></td>
            <td>: {{ $pembayaran->paid_at?->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>: {{ $pembayaran->tagihan->periode ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Metode</strong></td>
            <td>: {{ strtoupper($pembayaran->metode) }}</td>
        </tr>
    </table>

    <hr>

    <p class="total">
        Total Pembayaran: Rp {{ number_format($pembayaran->nominal,0,',','.') }}
    </p>

    <div class="footer">
        Dokumen ini sah dan diterbitkan secara digital oleh sistem OneN WiFi.
        <br>
        Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
    </div>

</div>

</body>
</html>