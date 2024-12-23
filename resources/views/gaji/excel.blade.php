<table class="table  table-bordered" id="table">
    <thead>
        <tr>
            <th width="38px">NO</th>
            <th width="270px">NAMA KARYAWAN</th>
            <th width="171px">TANGGAL MASUK</th>
            <th width="103px">KATEGORI</th>
            <th width="152px">POSISI</th>
            <th width="94px">TIPE GAJI</th>
            <th width="108px" class="text-right">RP <br> hari/jam</th>
            @foreach ($shift as $s)
                <th class="text-center" width="69px">ABSEN <br> {{ $s->ket }}</th>
            @endforeach
            <th class="text-center" width="120px">TOTAL ABSEN</th>
            <th class="text-center" width="120px">TOTAL GAJI</th>
            <th class="text-center" width="120px">KASBON</th>
            <th class="text-center" width="120px">DENDA</th>
            <th class="text-center" width="120px">SISA GAJI</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($gaji as $no => $g)
            <tr>
                <td>{{ $no + 1 }}</td>
                <td>{{ $g->nama }}</td>
                <td>{{ $g->tgl_masuk }}</td>
                <td>{{ $g->nm_status }}</td>
                <td>{{ $g->nm_posisi }}</td>
                <td>{{ $g->kategori }}</td>
                <td class="text-right">{{ $g->rp_gaji }}</td>
                @php
                    $ttl_shift = 0;
                @endphp
                @foreach ($shift as $s)
                    @php
                        $ket = $s->ket;
                        $ttl_shift += $g->$ket * ($g->rp_gaji * $s->waktu);
                    @endphp
                    <td class="text-center">{{ $g->$ket }}</td>
                @endforeach
                <td class="text-center">{{ $g->ttl }}</td>
                @if ($g->kategori == 'bulanan')
                    <td class="text-center">{{ $g->rp_gaji * $g->ttl }}
                    </td>
                @else
                    <td class="text-center">{{ $ttl_shift }}</td>
                @endif
                <td class="text-center">{{ $g->kasbon }}</td>
                <td class="text-center">{{ $g->denda }}</td>
                @if ($g->kategori == 'bulanan')
                    <td class="text-center">
                        {{ $g->rp_gaji * $g->ttl - ($g->kasbon + $g->denda) }}
                    </td>
                @else
                    <td class="text-center">
                        {{ $ttl_shift - ($g->kasbon + $g->denda) }}</td>
                @endif
            </tr>
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <th colspan="7">TOTAL</th>
            <th>=SUM(H2:H{{ $no + 2 }})</th>
            <th>=SUM(I2:I{{ $no + 2 }})</th>
            <th>=SUM(J2:J{{ $no + 2 }})</th>
            <th>=SUM(K2:K{{ $no + 2 }})</th>
            <th>=SUM(L2:L{{ $no + 2 }})</th>
            <th>=SUM(M2:M{{ $no + 2 }})</th>
            <th>=SUM(N2:N{{ $no + 2 }})</th>
            <th>=SUM(O2:O{{ $no + 2 }})</th>

        </tr>
    </tfoot>

</table>
