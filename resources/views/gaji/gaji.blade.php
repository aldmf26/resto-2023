@extends('template.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2 justify-content-center">
                    <div class="col-sm-12">

                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Gaji</h5>
                                <h5>{{ date('d-m-Y', strtotime($tgl1)) }} ~ {{ date('d-m-Y', strtotime($tgl2)) }}</h5>
                                <a href="#" data-toggle="modal" data-target="#view"
                                    class="btn btn-info btn-sm float-right"><i class="fas fa-eye"></i> View</a>
                                <a href="{{ route('gajiExport', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                                    class="btn btn-info btn-sm float-right mr-2"><i class="fas fa-file-excel"></i>
                                    Export</a>
                                <a href="{{ route('point_export_server', ['id_lokasi' => 1, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                                    class="btn btn-info btn-sm float-right mr-2"><i class="fas fa-file-excel"></i>
                                    Export Omset</a>
                            </div>
                            @include('flash.flash')
                            <div class="card-body">
                                <table class="table  table-bordered" id="table">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA KARYAWAN</th>
                                            <th>TANGGAL MASUK</th>
                                            <th>KATEGORI</th>
                                            <th>POSISI</th>
                                            <th>TIPE GAJI</th>
                                            <th class="text-right">RP <br> hari/jam</th>
                                            @foreach ($shift as $s)
                                                <th class="text-center">Absen <br> {{ $s->ket }}</th>
                                            @endforeach
                                            <th class="text-center">TOTAL ABSEN</th>
                                            <th class="text-center">TOTAL GAJI</th>
                                            <th class="text-center">KASBON</th>
                                            <th class="text-center">DENDA</th>
                                            <th class="text-center">SISA GAJI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gaji as $no => $g)
                                            <tr>
                                                <td>{{ $no + 1 }}</td>
                                                <td>{{ $g->nama }}</td>
                                                <td>{{ date('d-F-Y', strtotime($g->tgl_masuk)) }}</td>
                                                <td>{{ $g->nm_status }}</td>
                                                <td>{{ $g->nm_posisi }}</td>
                                                <td>{{ $g->kategori }}</td>
                                                <td class="text-right">{{ number_format($g->rp_gaji, 0) }}</td>
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
                                                    <td class="text-center">{{ number_format($g->rp_gaji * $g->ttl, 0) }}
                                                    </td>
                                                @else
                                                    <td class="text-center">{{ number_format($ttl_shift, 0) }}</td>
                                                @endif
                                                <td class="text-center">{{ number_format($g->kasbon, 0) }}</td>
                                                <td class="text-center">{{ number_format($g->denda, 0) }}</td>
                                                @if ($g->kategori == 'bulanan')
                                                    <td class="text-center">
                                                        {{ number_format($g->rp_gaji * $g->ttl - ($g->kasbon + $g->denda), 0) }}
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        {{ number_format($ttl_shift - ($g->kasbon + $g->denda), 0) }}</td>
                                                @endif
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <style>
        .modal-lg-max1 {
            max-width: 1100px;
        }
    </style>
    {{-- import --}}
    <form action="" method="post" enctype="multipart/form-data">
        <div class="modal fade" role="dialog" id="import" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Edit Gaji</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Dari</label>
                                <input required class="form-control" type="file" name=" file">
                            </div>
                        </div>

                    </div>


                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- ------ --}}
    <form action="" method="get">
        <div class="modal fade" role="dialog" id="view" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header btn-costume">
                        <h5 class="modal-title text-light" id="exampleModalLabel">View Gaji</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">Dari</label>
                                <input required class="form-control" type="date" name="tgl1" id="dari">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Sampai</label>
                                <input required class="form-control" type="date" name="tgl2" id="sampai">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class=" btn btn-sm btn-info">View</button>
                    </div>
                </div>
            </div>
        </div>
    </form>



    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <style>
        .modal-lg-max {
            max-width: 900px;
        }
    </style>



    {{-- ---------------- --}}
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#submit', function() {
                var dari = $('#dari').val()
                var sampai = $('#sampai').val()
                if (dari == '' || sampai == '') {
                    alert('Isi dulu Tanggalnya')
                } else {

                    $('#ketDari').text(dari)
                    $('#ketSampai').text(sampai)
                    $('#badan').load("{{ route('tabelGaji') }}?dari=" + dari + "&sampai=" + sampai,
                        "data",
                        function(response, status,
                            request) {
                            this; // dom element
                            $('#tableSum').DataTable({

                                "bSort": true,
                                // "scrollX": true,
                                "paging": true,
                                "stateSave": true,
                                "scrollCollapse": true
                            });
                        });
                }
                // alert(`dari : ${dari} sampai : ${sampai}`)
            })
        })
    </script>
@endsection
