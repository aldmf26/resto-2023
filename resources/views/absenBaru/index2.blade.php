@extends('template.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-left">Absen Tanggal : {{ date('d-F-Y', strtotime($tgl1)) }} ~
                                    {{ date('d-F-Y', strtotime($tgl2)) }}</h5>
                                <button type="button" class="btn btn-sm btn-info float-right ml-2" data-toggle="modal"
                                    data-target="#viewAbsen"><i class="fas fa-print"></i> View</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="dhead">#</th>
                                            <th class="text-nowrap dhead">Nama Karaywan</th>
                                            @foreach ($dates as $date)
                                                <th class="text-center dhead">{{ $date }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($karyawan as $no => $k)
                                            <tr>
                                                <td>{{ $no + 1 }}</td>
                                                <td class="text-nowrap">{{ $k['nama'] }}</td>
                                                @foreach ($dates as $date)
                                                    @php
                                                        $absen = Http::get(
                                                            'https://ptagafood.com/api/absenPrint?id_karyawan=1&date=1&bulan=1&tahun=2025',
                                                        );
                                                        $dt_absen = json_decode($absen, true);

                                                    @endphp
                                                    <td class="text-center">{{ $dt_absen['data']['absen'] }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
