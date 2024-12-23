@extends('template.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Absen Tanggal : {{ date('d-F-Y', strtotime($tgl)) }}</h5>
                            </div>
                            <div class="card-body">
                                <div id="load_absen"></div>
                                <input type="hidden" id="tgl" value="{{ $tgl }}">
                            </div>
                        </div>

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
