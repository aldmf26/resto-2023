<div class="row">

    <div class="col-lg-3">
        <label for="">Tgl Masuk</label>
        <input type="hidden" name="id_karyawan" value="{{ $karyawan->id_karyawan }}">
        <input type="date" name="tgl_masuk" class="form-control" value="{{ $karyawan->tgl_masuk }}">
    </div>
    <div class="col-lg-3">
        <label for="">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $karyawan->nama }}">
    </div>
    <div class="col-lg-3">
        <label for="">Status</label>
        <select name="status" id="" class="form-control">
            <option value="">- Pilih Status - </option>
            @foreach ($status as $s)
                <option value="{{ $s->id_status }}" {{ $s->id_status == $karyawan->id_status ? 'selected' : '' }}>
                    {{ $s->nm_status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-3">
        <label for="">Posisi</label>
        <select name="posisi" id="" class="form-control">
            <option value="">- Pilih Posisi - </option>
            @foreach ($posisi as $p)
                <?php if($p->id_posisi == 1){ ?>
                <?php continue; ?>
                <?php } ?>
                <option value="{{ $p->id_posisi }}" {{ $p->id_posisi == $karyawan->id_posisi ? 'selected' : '' }}>
                    {{ $p->nm_posisi }}</option>
            @endforeach
        </select>
    </div>

</div>
<br>

@if (empty($gaji->kategori))
    <div class="row">
        <div class="col-lg-3">
            <label for="">Kategori Gaji</label>
            <select name="kategori_gaji" id="" class="form-control cek_kategori" required>
                <option value="">-Pilih Katgeori-</option>
                <option value="bulanan">bulanan</option>
                <option value="shift">shift</option>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="">Rp Gaji</label>
            <input class="form-control" required type="text" value="{{ $gaji->rp_gaji ?? 0 }}" name="rp_gaji"
                required>
            <p class="text-danger font-weight-bold note_shift" hidden>note : isi
                rp/jam nya saja</p>
            <p class="text-danger font-weight-bold note_bulanan" hidden>note : isi rp/hari nya saja</p>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-lg-3">
            <label for="">Kategori Gaji</label>
            <select name="kategori_gaji" id="" class="form-control cek_kategori" required>
                <option value="">-Pilih Katgeori-</option>
                <option value="bulanan" {{ 'bulanan' == $gaji->kategori ? 'selected' : '' }}>bulanan</option>
                <option value="shift" {{ 'shift' == $gaji->kategori ? 'selected' : '' }}>shift</option>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="">Rp Gaji</label>
            <input class="form-control" required type="text" value="{{ $gaji->rp_gaji ?? 0 }}" name="rp_gaji"
                required>
            <p class="text-danger font-weight-bold note_shift" {{ 'shift' == $gaji->kategori ? '' : 'hidden' }}>note :
                isi
                rp/jam nya saja</p>
            <p class="text-danger font-weight-bold note_bulanan" {{ 'bulanan' == $gaji->kategori ? '' : 'hidden' }}>
                note
                : isi rp/hari nya saja</p>
        </div>
    </div>
@endif
