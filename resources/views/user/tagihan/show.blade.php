<h3>Detail Tagihan</h3>

<p>Periode: {{ $tagihan->periode }}</p>
<p>Nominal: Rp {{ number_format($tagihan->nominal) }}</p>
<p>Status: {{ $tagihan->status }}</p>

@if($tagihan->status == 'belum bayar' || $tagihan->status == 'menunggak')
<form action="{{ route('user.tagihan.bayar', $tagihan->id) }}" method="POST">
    @csrf
    <button>Bayar</button>
</form>
@endif

@if($tagihan->status == 'lunas')
<b style="color:green">LUNAS</b>
@endif
