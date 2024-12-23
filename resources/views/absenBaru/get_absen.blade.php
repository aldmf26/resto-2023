<table class="table table-bordered" id="tabelAbsen">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            @foreach ($shift as $s)
                <th>{{ $s->ket }}</th>
            @endforeach
            <th>Off</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($karyawan as $k)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $k->nama }}</td>
                @foreach ($shift as $s)
                    <td>
                        <a href="#"
                            class="btn  btn-sm btn-block click_absen {{ $k->shift_id == $s->id_shift ? 'btn-success' : 'btn-secondary' }}"
                            id_shift="{{ $s->id_shift }}" id_karyawan="{{ $k->id_karyawan }}">{{ $s->ket }}</a>
                    </td>
                @endforeach
                <td>
                    <a href="#"
                        class="btn btn-secondary btn-sm btn-block click_absen {{ empty($k->shift_id) ? 'btn-success' : 'btn-secondary' }}"
                        id_shift="0" id_karyawan="{{ $k->id_karyawan }}">Off</a>
                </td>
            </tr>
        @endforeach
    </tbody>

</table>
