<div>
    <input type="text" wire:model="keyword" placeholder="Cari Menu..." class="form-control"
        id="search_field" name="keyword" autofocus><br>

    <table class="table  ">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Level</th>
                <th>Kode Menu</th>
                <th>Nama Menu</th>
                <th>Tipe</th>
                <th>Station</th>
                <th>Distribusi</th>
                <th></th>
                <th>On/Off</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($menu as $index => $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->kategori }}</td>
                    <!--<td></td>-->
                    @if (empty($m->handicapId))
                        <td></td>
                    @else
                        <td>{{ $m->handicap }} ({{ $m->point }} Point)</td>
                    @endif
                    <td>{{ $m->kd_menu }}</td>
                    <td>{{ ucwords(Str::lower($m->nm_menu)) }}</td>
                    <td>{{ $m->tipe }}</td>
                    <td>{{ $m->nm_station }}</td>
                    <td style="white-space: nowrap;">
                        @foreach ($m->harga as $h)
                            {{ $h->distribusi->nm_distribusi ?? '' }} <br>
                        @endforeach
                    </td>

                    <td>
                        @foreach ($m->harga as $h)
                            :{{ number_format($h->harga, 0) }} <br>
                        @endforeach
                        <a href="#" id_menu="{{ $m->id_menu }}" class="btn btn-new btnPlusDistribusi"
                            style="background-color: #F7F7F7;"><i style="color: #B0BEC5;"><i
                                    class="fas fa-plus"></i></a>
                    </td>
                    <td>
                        <label class="switch float-left">
                            <input type="checkbox" class="form-checkbox1" id="form-checkbox"
                                id_checkbox="{{ $m->id_menu }}" {{ $m->aktif == 'on' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <input name="monitor"
                            class="swalDefaultSuccess form-password nilai{{ $m->id_menu }} form-control"
                            value="{{ $m->aktif }}" hidden>
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="" data-toggle="modal" data-target="#edit_data{{ $m->id_menu }}"
                            id_menu="{{ $m->id_menu }}" id_lokasi="{{ $id_lokasi }}"
                            class="btn edit_menu btn-new editMenu" style="background-color: #F7F7F7;"><i
                                style="color: #B0BEC5;"><i class="fas fa-edit"></i></a>
                        {{-- <a onclick="return confirm('Apakah ingin hapus ?')"
                            href="{{ route('deleteMenu', ['id_menu' => $m->id_menu, 'id_lokasi' => $id_lokasi, 'keyword' => $keyword]) }}"
                            class="btn btn-new" style="background-color: #F7F7F7;"><i style="color: #B0BEC5;"><i
                                    class="fas fa-trash-alt"></i></a> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {{-- {{ $menu->links() }} --}}
</div>
