<div class="card">
    <div class="card-header">
        <a href="{{ route('excel_cek_invoice', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
            class="btn btn-sm btn-info float-right"><i class="fas fa-file-excel"></i> Export</a>
        <a href="{{ route('print_cek_invoice', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
            class="btn btn-sm btn-info float-right mr-2"><i class="fas fa-print"></i> Print</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table width="100%" class="table table-bordered" id="table_cek" style="font-size: 11px">
                <thead>
                    <tr>
                        <th style="font-size: 11px">#</th>
                        <th style="font-size: 11px">Tanggal</th>
                        <th style="font-size: 11px">No Nota</th>
                        <th style="font-size: 11px">Ttl Rp</th>
                        <th style="font-size: 11px">Dp</th>
                        @foreach ($pembayaran as $p)
                            <th style="font-size: 11px">
                                {{ $p->nm_akun }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rowNumber = 1;
                    @endphp
                    @foreach ($invoice_format as $no => $i)
                        <tr>
                            <td>{{ $rowNumber }}</td>
                            <td>{{ date('d-m-Y', strtotime($i->tgl)) }}</td>
                            <td>{{ $i->no_nota }}</td>
                            <td align="right">{{ number_format($i->total_orderan, 0) }}</td>
                            <td align="right">{{ number_format($i->dp, 0) }}</td>
                            @foreach ($pembayaran as $p)
                                <td align="right">
                                    @if ($p->nm_akun == $i->nm_akun)
                                        {{ number_format($i->nominal, 0) }}
                                    @else
                                        0
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @php
                            $rowNumber++;
                        @endphp
                    @endforeach
                    @foreach ($majo as $no => $i)
                        <tr>
                            <td>{{ $rowNumber }}</td>
                            <td>{{ date('d-m-Y', strtotime($i->tgl)) }}</td>
                            <td>{{ $i->no_nota }}</td>
                            <td align="right">{{ number_format($i->bayar, 0) }}</td>
                            <td align="right">0</td>
                            @foreach ($pembayaran as $p)
                                <td align="right">
                                    @if ($p->nm_akun == $i->nm_akun)
                                        {{ number_format($i->nominal, 0) }}
                                    @else
                                        0
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @php
                            $rowNumber++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ asset('assets') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script>
    $('#table_cek').DataTable({

        "bSort": true,
        // "scrollX": true,
        "paging": true,
        "stateSave": true,
        "scrollCollapse": true
    });
</script>
