<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome-free/css/all.min.css">
    <title>{{ $title }}</title>
    <style>
        .dhead {
            background-color: #acacac !important;
            color: white;
        }

        @media print {
            .btn_export {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="text-center mb-4 mt-4 fw-bold">ABSEN KARYAWAN</h5>
                <a href="{{ route('export_absen', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                    class="btn btn-success mb-4 btn_export"><i class="fas fa-file-excel"></i>
                    Export</a>
                <table class="table table-bordered table-responsive">
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
                                <td class="text-nowrap">{{ $k->nama }}</td>
                                @foreach ($dates as $date)
                                    @php
                                        $absen = DB::selectOne("SELECT  b.ket 
                                        FROM absennew as a
                                        left join tb_shift as b on b.id_shift = a.shift_id
                                        where a.karyawan_id = $k->id_karyawan and DAY(a.tgl) = '$date' and MONTH(a.tgl) = '$bulan' and YEAR(a.tgl) = '$tahun' 
                                        ");
                                    @endphp
                                    <td class="text-center">{{ $absen->ket ?? 'OFF' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    -->
</body>

</html>
