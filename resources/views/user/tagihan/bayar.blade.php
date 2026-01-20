<h3>Pembayaran Tagihan</h3>

<p>Periode: {{ $tagihan->periode }}</p>
<p>Nominal: Rp {{ number_format($tagihan->nominal) }}</p>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<button id="pay-button">Bayar Sekarang</button>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}');
};
</script>
