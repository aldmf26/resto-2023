<?php

$file = 'LAPORAN CLOSINGAN.xls';
header('Content-type: application/vnd-ms-excel');
header("Content-Disposition: attachment; filename=$file");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CLOSINGAN</title>

    <style>
        p {
            font-size: 18px;
        }
    </style>

<body>

    <div class="row">
        <div class="col-lg-12">
            <h2 style="text-align: center;">Laporan Closingan</h2>
            <h3 style="text-align: center;"> {{ date('d M Y', strtotime($tgl1)) }} ~
                {{ date('d M Y', strtotime($tgl2)) }}</h3>
            <h3 style="text-align: center;">{{ $lokasi }}</h3>
            <table width="100%" border="1">
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


</body>

</html>
