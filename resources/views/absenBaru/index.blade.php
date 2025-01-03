@extends('template.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-left">Absen Tanggal : {{ date('d-F-Y', strtotime($tgl)) }}</h5>
                                <button type="button" class="btn btn-sm btn-info float-right ml-2" data-toggle="modal"
                                    data-target="#view"><i class="fas fa-print"></i> Print</button>
                                <button type="button" class="btn btn-sm btn-info float-right ml-2" data-toggle="modal"
                                    data-target="#viewAbsen"><i class="fas fa-print"></i> View</button>
                            </div>
                            <div class="card-body">
                                <div id="load_absen"></div>
                                <input type="hidden" id="tgl" value="{{ $tgl }}">
                            </div>
                        </div>
                        <form action="{{ route('print_absen') }}" method="get">
                            <div class="modal fade" role="dialog" id="view" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header btn-costume">
                                            <h5 class="modal-title text-light" id="exampleModalLabel">View Absen</h5>
                                            <button type="button" class="close text-light" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="">Dari</label>
                                                    <input required class="form-control" type="date" name="tgl1"
                                                        id="dari">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="">Sampai</label>
                                                    <input required class="form-control" type="date" name="tgl2"
                                                        id="sampai">
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
                        <form action="" method="get">
                            <div class="modal fade" role="dialog" id="viewAbsen" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header btn-costume">
                                            <h5 class="modal-title text-light" id="exampleModalLabel">View Absen</h5>
                                            <button type="button" class="close text-light" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="">Tanggal</label>
                                                    <input required class="form-control" type="date" name="tgl">
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

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            get_absen('{{ $tgl }}');

            function get_absen(tgl) {

                $.ajax({
                    type: "get",
                    url: "{{ route('get_absen') }}",
                    data: {
                        tgl: tgl
                    },
                    success: function(response) {
                        $('#load_absen').html(response);
                        $('#tabelAbsen').DataTable({

                            "bSort": true,
                            "scrollY": true,
                            "paging": true,
                            "stateSave": true,
                            "scrollCollapse": true
                        });
                    }
                });
            }

            $(document).on('click', '.click_absen', function(e) {
                e.preventDefault();
                var id_shift = $(this).attr('id_shift');
                var id_karyawan = $(this).attr('id_karyawan');
                var tgl = $('#tgl').val();

                $.ajax({
                    type: "get",
                    url: "{{ route('save_absen_baru') }}",
                    data: {
                        id_shift: id_shift,
                        id_karyawan: id_karyawan,
                        tgl: tgl
                    },
                    success: function(response) {
                        get_absen(tgl);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'success',
                            title: 'Absen berhasil ditambhkan'
                        });
                    }
                });



            })


        });
    </script>
@endsection
