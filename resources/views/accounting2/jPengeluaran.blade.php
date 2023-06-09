@extends('accounting.template.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                   
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                      @include('accounting.template.flash')
                        <div class="card">
                          @php
                           

                            $tDebit = DB::table('tb_jurnal')->where([['id_lokasi', Request::get('acc')],['id_buku',3]])->whereBetween('tgl', [$dari,$sampai])->sum('debit');
                            $tKredit = DB::table('tb_jurnal')->where([['id_lokasi', Request::get('acc')],['id_buku',3]])->whereBetween('tgl', [$dari,$sampai])->sum('kredit');
                          @endphp
                            <div class="card-header">
                                <h5 class="float-left">Jurnal Pengeluaran {{ date('d F Y', strtotime($dari)); }} ~ {{ date('d F Y', strtotime($sampai)); }}</h5>
                                <a href="" data-toggle="modal" data-target="#viewtgl"
                                    class="btn btn-info btn-sm float-right mr-1"><i class="fas fa-eye"></i> View</a>
                                <a href="{{ route('exportJPengeluaran',['dari' => $dari, 'sampai' => $sampai]) }}"
                                    class="btn btn-info btn-sm float-right mr-1"><i class="fas fa-file-export"></i> Export
                                </a>
                                <a href="" data-toggle="modal" data-target="#tambah"
                                    class="btn btn-info btn-sm float-right mr-1"><i class="fas fa-plus"></i>
                                    Pengeluaran</a>
                            </div>
                            <div class="card-body">
                                <div id="table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-striped dataTable no-footer" id="table" role="grid"
                                                aria-describedby="table_info">
                                                <thead>
                                                  
                                                    <tr role="row" class="table-info">
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>No Nota</th>
                                                        <th>No Id</th>
                                                        <th>No Akun</th>
                                                        <th>Nama Akun</th>
                                                        <th>Tujuan</th>
                                                        <th>Keterangan</th>
                                                        <th>Debit ({{ number_format($tDebit, 0) }})</th>
                                                        <th>Kredit ({{ number_format($tKredit, 0) }})</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1;                                        
                                                    @endphp
                                                    @foreach ($jurnal as $a)                                                                                                        
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{date('d-m-y', strtotime($a->tgl))}}</td>
                                                    <td>{{$a->no_nota}}</td>
                                                    <td>{{$a->no_urutan}}</td>
                                                    <td>{{$a->no_akun}}</td></td>
                                                    <td>{{$a->nm_akun}}</td>
                                                    <td>{{$a->ket}}</td>
                                                    <td>{{$a->ket2}}</td>
                                                    <td>{{number_format($a->debit,0)}}</td>
                                                    <td>{{number_format($a->kredit,0)}}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary btnEditJ" kd_gabungan='<?= $a->kd_gabungan ?>' data-toggle="modal" data-target="#edit"><i class="fa fa-edit"></i></button>
                                                        <a href="{{ route('deletejPengeluaran', ['kd_gabungan' => $a->kd_gabungan,'id_lokasi' => Request::get('acc'), 'dari' => $dari, 'sampai' => $sampai]) }}" onclick="return confirm('Apakah ingin dihapus ?')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-trash"></i></a>
                                                    </td>
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
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    {{-- modal export pertanggal --}}
    <div class="modal fade" id="edit" role="dialog">
      <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
       
        <div id="get_jurnal"></div>
      </div>
    </div>

<form action="{{ route('jPengeluaran', ['acc' => Request::get('acc')]) }}" method="get">
  <div class="modal fade" id="viewtgl" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md-6" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">View Pertanggal</h5>
                  <button type="button" class="close" data-dismiss="modal"
                      aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <input type="hidden" name="acc" value="{{Request::get('acc')}}">
                      <div class="col-md-6">
                          <label for="">Dari</label>
                          <input required type="date" name="dari" class="form-control mb-3">
                      </div>
                      <div class="col-md-6">
                          <label for="">Sampai</label>
                          <input required type="date" name="sampai" class="form-control mb-3">
                      </div>
                  </div>
                  <div class="modal-footer">
                      <input type="submit" name="simpan" value="Simpan" id="tombol"
                          class="btn btn-primary mt-3">
                      <button type="button" class="btn btn-secondary  mt-3"
                          data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</form>
    <style>
        .modal-lg-max {
            max-width: 800px;
        }

    </style>
    {{-- add akun --}}

    <form action="" method="post" id="form-jurnal">
    @csrf
    <div class="modal fade" id="tambah" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg-max" role="document">
            <div class="modal-content">
                <div class="modal-header bg-costume">
                  <h5 class="modal-title" id="exampleModalLabel">Jurnal Pengeluaran</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id_lokasi" value="{{Request::get('acc')}}">
                  <div class="row">
        
                    <div class="col-sm-3 col-md-3">
                      <div class="form-group">
                        <label for="list_kategori">Tanggal</label>
                        <input class="form-control" type="date" name="tgl" id="tgl_peng" value="<?= date('Y-m-d') ?>" required>
        
                      </div>
                    </div>
                    <div class="mt-3 ml-1">
                      <p class="mt-4 ml-2 text-warning"><strong>Db</strong></p>
                    </div>
                    <div class="col-sm-4 col-md-4">
                      <div class="form-group">
                        <label for="list_kategori">Akun</label>
                        <select name="id_akun" id="id_pilih" class="form-control select2 id_dipilih" required="">
                          <option value="">- Pilih Akun -</option>
                          @foreach ($akun as $ak)
                              <option value="{{ $ak->id_akun }}">{{ $ak->nm_akun }}</option>
                          @endforeach
                        </select>
                         
                      </div>
                    </div>
        
                    <div class="col-sm-2 col-md-2">
                      <div class="form-group">
                        <label for="list_kategori">Debit</label>
                        <input type="number" class="form-control total " id="total" name="total" readonly>
                      </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                      <div class="form-group">
                        <label for="list_kategori">Kredit</label>
                        <input type="number" class="form-control" readonly="">
                      </div>
                    </div>
        
                    <div class="col-sm-3 col-md-3">
                      <!-- <div class="form-group">
                        <input class="form-control" type="text" name="no_urutan" placeholder="Nomor id" required>
                      </div> -->
                    </div>
        
                    <div class="mt-1">
                      <p class="mt-1 ml-3 text-warning"><strong>Cr</strong></p>
                    </div>
        
                    <div class="col-sm-4 col-md-4">
                      <div class="form-group">
                        <select name="metode" id="metode" class="form-control select2" required="">
                          <option value="" data-select2-id="13">-Pilih Akun-</option> 
                          @foreach ($akun as $a)
                              <option value="{{$a->id_akun}}">{{$a->nm_akun}}</option>
                          @endforeach                                       
                        </select>                      
                      </div>
                    </div>       
                    <div class="col-sm-2 col-md-2">
                      <div class="form-group">
                        <input type="number" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                      <div class="form-group">
        
                        <input type="number" class="form-control total" id="total1" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <h2 class="text-danger" id="testing"></h2>
                  </div>
                  <hr>
                  <div class="modal-body detail" id="biayaUtama">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          
                          <label for="list_kategori">No id</label>
                          <input type="text" class="form-control input_detail input_biaya" name="no_id[]" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Post Center</label>
                          <select name="id_post_center[]" class="form-control select2 id_post">
        
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Tujuan </label>
                          <input type="text" class="form-control input_detail input_biaya" name="keterangan[]" required>
                          
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Keterangan</label>
                          <input type="text" class="form-control input_detail input_biaya" name="ket2[]" required>
                        </div>
                      </div>
                      
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Satuan</label>
                          <select name="id_satuan[]" class="form-control select2 satuan input_detail input_biaya" required>
                            <?php foreach ($satuan as $p) : ?>
                              <option value="<?= $p->id ?>"><?= $p->n ?></option>
                            <?php endforeach; ?>
                          </select>
        
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Qty</label>
                          <input type="text" class="form-control input_detail input_biaya qty_monitoring1" qty=1 name="qty[]" required>
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Total Rp</label>
                          <input type="text" class="form-control  input_detail input_biaya total_rp total_rp1" name="ttl_rp[]" total_rp='1' required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for=""><span style="font-size: 15px; color:red">(eg : talang, atap / jika tidak perlu diisi jangan diisi)</span></label>
                      </div>
                    </div>
        
        
                    <div id="detail_monitoring">
        
                    </div>
        
                    <div align="right" class="mt-2">
                      <button type="button" id="tambah_monitoring" class="btn-sm btn-success">Tambah</button>
                    </div>
                  </div>

                  <div id="atk" class="detail">
                    <hr>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">No Id</label>
                          <input type="text" class="form-control input_detail input_atk" name="no_id[]" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Tujuan</label>
                          <input type="text" class="form-control input_detail input_atk" name="keterangan[]" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Keterangan</label>
                          <input type="text" class="form-control input_detail input_atk" name="ket2[]" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Post Center</label>
                          <select name="id_post_center[]" class="form-control select2 id_post">
        
                          </select>
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Satuan</label>
                          <select name="id_satuan[]" class="form-control select2 satuan input_detail input_atk" required>
                            <?php foreach ($satuan as $p) : ?>
                              <option value="<?= $p->id ?>"><?= $p->n ?></option>
                            <?php endforeach; ?>
                          </select>
        
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Qty</label>
                          <input type="text" class="form-control input_detail input_atk qty_monitoring1" qty=1 name="qty[]" required>
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Total Rp</label>
                          <input type="text" class="form-control  input_detail input_atk total_rp total_rp1" name="ttl_rp[]" total_rp='1' required>
                        </div>
                      </div>
        
                    </div>
        
        
                    <div id="detail_atk">
        
                    </div>
        
                    <div align="right" class="mt-2">
                      <button type="button" id="tambah_atk" class="btn-sm btn-success">Tambah</button>
                    </div>
        
                  </div>

                  <div id="peralatan" class="detail">
                    <hr>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">No id</label>
                          <input type="text" name="no_id[]" class="form-control input_detail input_peralatan">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Barang</label>
                          <input type="text" name="nm_barang[]" class="form-control input_detail input_peralatan">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Penangung Jawab</label>
                          <select name="id_penanggung[]" class="form-control select2 satuan input_detail input_peralatan" required>
                            <option>--Pilih penanggung jawab--</option>
                            <?php foreach ($nm_penanggung as $n) : ?>
                              <option value="<?= $n->id_penanggung ?>"><?= $n->nm_penanggung ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Lokasi</label>
                          <select name="id_lokasi[]" class="form-control select2 satuan input_detail input_peralatan" required>
                            <option selected value="{{Request::get('acc') == 1 ? '1' : '2'}}" disabled>{{Request::get('acc') == 1 ? 'TAKEMORI' : 'SOONDOBU'}}</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Satuan Barang</label>
                          <select name="id_satuan[]" class="form-control select2 satuan input_detail input_peralatan" required>
                            <?php foreach ($satuan as $p) : ?>
                              <option value="<?= $p->id ?>"><?= $p->n ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Qty</label>
                          <input type="text" class="form-control input_detail input_peralatan qty_monitoring1" qty=1 name="qty[]" required>
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Total Rp</label>
                          <input type="text" class="form-control  input_detail input_peralatan total_rp total_rp1" name="ttl_rp[]" total_rp='1' required>
                        </div>
                      </div>
        
                      <div class="col-lg-6">
                        <table class="table table-bordered table-sm" width="100%">
                          <thead style="text-align: center;">
                            <tr>
                              <th></th>
                              <th width="15%">Nama Kelompok</th>
                              <th width="15%">Umur</th>
                              <th width="70%">Barang</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($peralatan as $a) : ?>
                              <tr>
                                <td><input type="checkbox" name="id_kelompok[]" id="" value="<?= $a->id_kelompok ?>"></td>
                                <td><?= $a->nm_kelompok ?></td>
                                <td><?= $a->umur ?> <?= $a->satuan ?></td>
                                <td><?= $a->barang_kelompok ?></td>
                              </tr>
                            <?php endforeach ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
        
        
        
        
                    <div id="detail_peralatan">
        
                    </div>
        
                    <div align="right" class="mt-2">
                      <button type="button" id="tambah_peralatan" class="btn-sm btn-success">Tambah</button>
                    </div>
        
                  </div>

                  <div id="aktiva" class="detail">
                    <hr>
        
                    <!-- testing aktiva -->
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">No id</label>
                          <input type="text" name="no_id" class="form-control input_detail input_aktiva" placeholder="No id">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Post Center</label>
                          <select name="id_post" id="id_post" class="form-control select2 input_detail input_aktiva">
        
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Keterangan</label>
                          <input type="text" class="form-control" name="ket">
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Satuan</label>
                          <select name="id_satuan" class="form-control select2 satuan input_detail input_aktiva" required>
                            <?php foreach ($satuan as $p) : ?>
                              <option value="<?= $p->id ?>"><?= $p->n ?></option>
                            <?php endforeach; ?>
                          </select>
        
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label for="list_kategori">Qty</label>
                          <input type="text" class="form-control input_detail input_aktiva qty_aktiva qty_monitoring1" name="qty" id="txt3" onkeyup="sum();" value="1" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Rp/Satuan</label>
                          <input type="text" name="rp_satuan" class="form-control ttlp total_penyesuaian rp_satuan_aktiva  input_detail input_aktiva " id="ttlp" onkeyup="sum();" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">PPN</label>
                          <input type="text" class="form-control input_detail total_rp_new1 input_aktiva total_rp_new" name="ppn" id="txt2" onkeyup="sum();">
                        </div>
                      </div>
        
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Rp + Pajak</label>
                          <input type="text" class="form-control total_penyesuaian  input_detail input_aktiva pajak_aktiva" id="total2" name="ttl_rp" required>
                        </div>
                      </div>
                    </div>
        
        
                    <hr>
                    <div class="row justify-content-center">
                      <div class="col-lg-12">
                        <table class="table table-bordered table-sm" width="100%">
                          <thead style="text-align: center;">
                            <tr>
                              <th></th>
                              <th width="15%">Nama Kelompok</th>
                              <th width="15%">Umur</th>
                              <th width="70%">Barang</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($aktiva as $a) : ?>
                              <tr>
                                <td><input type="radio" name="id_kelompok" id="" value="<?= $a->id_kelompok ?>"></td>
                                <td><?= $a->nm_kelompok ?></td>
                                <td><?= $a->umur ?> Tahun</td>
                                <td><?= $a->barang_kelompok ?></td>
                              </tr>
                            <?php endforeach ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
        
                  </div>

                  <div id="stok" class="detail">
                    <hr>
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="form-group">
                          <label for="">Biaya Penunjang</label>
                          <input type="text" class="form-control input_detail input_stok" name="biaya_penunjang" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">No Id</label>
                          <input type="text" class="form-control input_detail input_stok" name="no_id[]" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Keterangan</label>
                          <input type="text" class="form-control input_detail input_stok" name="keterangan[]" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">List Bahan = Resep</label>
                          <select name="id_list_bahan[]" detail="1" id="id_list_bahan1" class="id_list_bahan form-control select2 satuan input_detail input_stok listBahan" required>
                            {{-- <option value="0">- PIlih Makanan -</option>
                            @foreach ($lBahanDaging as $lb)
                                <option value="{{ $lb->id_list_bahan }}">{{ $lb->nm_bahan }}</option>
                            @endforeach --}}
                          </select>
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Satuan = Resep</label>
                          <input type="hidden" id="idSatuanResep1" readonly name="id_satuan[]" class="form-control input_detail input_stok">
                          <input type="text" id="satuanResep1" readonly  class="form-control input_detail input_stok">
                          <span class="text-danger" style="white-space: nowrap"><em>Satuan mengikuti resep</em></span>
        
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="list_kategori">Merk Bahan</label>
                          <select name="id_merk_bahan[]" id="id_merk_bahan1" class="form-control select2 satuan input_detail input_stok merkBahan " required>
                            <option value="0">- PIlih Merk -</option>
                            <div id="km"></div>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Qty</label>
                          <input type="text" class="form-control input_detail input_stok qty_monitoring1" qty=1 name="qty[]" required>
                        </div>
                      </div>
        
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="list_kategori">Total Rp</label>
                          <input type="text" class="form-control  input_detail input_stok total_rp total_rp1" name="ttl_rp[]" total_rp='1' required>
                        </div>
                      </div> 
        
                    </div>
        
        
                    <div id="detail_stok">
        
                    </div>
        
                    <div align="right" class="mt-2">
                      <button type="button" id="tambah_stok" class="btn-sm btn-success">Tambah</button>
                    </div>
        
                  </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary pen" id="save_btn">Edit/save</button>
                </div>
              </div>
        </div>
    </div>
</form>

{{-- tambah bahan --}}
<form id="modalBahan">
  @csrf
  <div class="modal fade tbhBahan" id="tbhBahan" >
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-costume">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah Bahan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-lg-5">
                          <label for="">Nama Bahan</label>
                          <input type="text" name="nm_bahan" id="tbh_namaBahan" class="form-control">
                      </div>
                      <div class="col-lg-3">
                          <label for="">Satuan</label>
                              @php
                                  $sat = DB::table('tb_satuan')->whereIN('id', [12,18,22,24,25,26])->get();
                              @endphp
                              <select class="form-control select" id="tbh_idSatuan" name="id_satuan">
                              <option value="">- Pilih Satuan -</option>
                              @foreach ($sat as $a)
                                  <option value="{{ $a->id }}">{{ $a->n }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-4">
                          <label for="">Kategori</label>
                          @php
                              $kat = DB::table('tb_kategori_makanan')->where('id_lokasi', $id_lokasi)->get();
                          @endphp
                              <select class="form-control select" name="kategori" id="tbh_kategori">
                                  <option value="">- Pilih Kategori -</option>
                                  @foreach ($kat as $k)
                                      <option value="{{ $k->id_kategori_makanan }}">{{ $k->nm_kategori }}</option>
                                  @endforeach
                          </select>
                      </div>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save/Edit</button>
              </div>
          </div>
      </div>
  </div>
</form>
{{-- ----------------- --}}

{{-- tambah merk --}}
<form id="modalMerk">
  @csrf
  <div class="modal fade tbhMerk" id="tbhMerk">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-costume">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah Merk Bahan</h5>
                  <button type="button" class="close merkB" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-lg-6">
                          <label for="">Merk Bahan</label>
                          <input required type="text" class="form-control" id="nm_merk" name="nm_merk">
                      </div>
                      <div class="col-lg-6">
                          <label for="">Nama Bahan</label>
                          <select required name="id_list_bahan" id="id_list_bahan" class="form-control select2">
                              <option value="0">- Pilih Bahan -</option>
                              @php
                                  $bahan = DB::table('tb_list_bahan')->where('id_lokasi', $id_lokasi)->get();
                              @endphp
                              @foreach ($bahan as $b)
                                  <option value="{{ $b->id_list_bahan }}">{{ $b->nm_bahan }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary merkB">Close</button>
                  <button type="submit" id="btnSimpan" class="btn btn-primary btnSimpan">Save/Edit</button>
              </div>
          </div>
      </div>
  </div>
</form>
{{-- -------------------- --}}



<!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>

@endsection

@section('script')

    <script>
      $('#table').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
                
        function sum()
        {
          var input1 = document.getElementById('ttlp').value;
          var input2 = document.getElementById('txt2').value;
          var input3 = document.getElementById('txt3').value;


          var result = (parseInt(input1) * parseInt(input3)) + parseInt(input2);
          var result2 = parseInt(input1) * parseInt(input3);
          if (!isNaN(result)) {
            document.getElementById('total').value = result;
            document.getElementById('total1').value = result;
            document.getElementById('total2').value = result;
          } else {
            document.getElementById('total').value = result2;
            document.getElementById('total1').value = result2;
            document.getElementById('total2').value = result2;
          }
        }
    </script>
    <script>
        hide_default();

        function hide_default() {
          $('.detail').hide();
          $('.input_detail').attr('disabled', 'true');
        }

        $('#id_pilih').change(function(){
          var id_akun = $(this).val()
          // alert(id_akun)
          var monitoring = []

          if(id_akun == 74 || id_akun == 112){
            hide_default();
            $('#atk').show();
            $('.input_atk').removeAttr('disabled', 'true');
            $('#form-jurnal').attr("action", "{{route('addjAtk')}}");
          } else if(id_akun == 71 || id_akun == 109) {
            hide_default();
            $('#peralatan').show();
            $('.input_peralatan').removeAttr('disabled', 'true');
            $('#form-jurnal').attr("action", "{{route('addjPeralatan')}}");
          } else if(id_akun == 143 || id_akun == 146) {
            hide_default();
            $('#aktiva').show();
            $('.input_aktiva').removeAttr('disabled', 'true');
            $("#form-jurnal").attr("action", "{{route('addjAktiva')}}");
          } else if(id_akun == 228 || id_akun == 229) {
            hide_default();
            $('#stok').show();
            $('.input_stok').removeAttr('disabled', 'true');
            $("#form-jurnal").attr("action", "{{route('addjStok')}}");
          }
          else {
            // alert(id_akun)
            hide_default();
            $('#biayaUtama').show();
            $('.input_biaya').removeAttr('disabled', 'true');
            $('#form-jurnal').attr("action", "{{route('addjPengeluaran')}}");
          }

          $("body").on("keyup", ".total_rp", function() {


            var debit = 0;

            // console.log(detail);

            $(".total_rp:not([disabled=disabled]").each(function() {
              debit += parseFloat($(this).val());
            });
            $('.total').val(debit);
            });
          })

        $("#id_pilih").change(function (e) { 
            var id_pilih = $(this).val();
            $('.id_post').load("{{route('getPost')}}?id_pilih="+id_pilih, "data", function (response, status, request) {
              this; // dom element
              
            });
        });
  
        

        $("#metode").change(function (e) { 
            var id_pilih = $(this).val();
            $('#id_post').load("{{route('getPost2')}}?id_pilih="+id_pilih, "data", function (response, status, request) {
              this; // dom element
              $('#id_post').change(function (e) { 
                var id_pilih = $(this).val();
                var qty = $('.qty_aktiva').val();
                $.ajax({
                  type: "get",
                  url: "{{route('getHargaAktiva')}}?id_pilih="+id_pilih,
                  success: function (data) {
                    var ppn = (parseFloat(data) * parseFloat(qty)) * 0.1;
                    var total = parseFloat(data) + parseFloat(ppn);
                    
                    $('.ttlp').val(data);
                    $('#txt2').val(ppn);
                    $('#total2').val(total);
                    $('.total').val(total);
                  }
                });
              });
            });
        });
        $('.rp_satuan_aktiva').keyup(function (e) { 
          var rp_beli = parseFloat($(this).val());
            var detail = $(this).attr('rp_satuan');

            var qty = parseFloat($('.qty_aktiva').val());
            var ttl_harga_new = (rp_beli * qty) * 0.1;
            h = isNaN(ttl_harga_new) ? 0 : ttl_harga_new

            if (isNaN(h)) {
              var rp_pajak = (rp_beli * qty);
            } else {
              var rp_pajak = (rp_beli * qty) + h;
            }

            // p = isNaN(rp_pajak) ? 0 : rp_pajak

            // console.log(rp_beli);
            $('.total_rp_new').val(h);


            $('.pajak_aktiva').val(rp_pajak);

            var debit = 0;

            $(".pajak_aktiva:not([disabled=disabled]").each(function() {
              debit += parseFloat($(this).val());
            });
            $('.total').val(debit);
        });
    </script>
    <script>
$(document).ready(function () {
  $('.btnEditJ').click(function() {
      var kd_gabungan = $(this).attr("kd_gabungan");
      $.ajax({
        url: "<?= route('edit_jurnal'); ?>?kd_gabungan=" + kd_gabungan,
        method: "GET",
        success: function(data) {
          $('#get_jurnal').html(data);
          $('.select').select2()
        }
      });
    
    });

       

        $("#tambah_monitoring").click(function (e) { 
          count_monitoring = count_monitoring + 1;
          var id_pilih = $('#id_pilih').val();
          var cn = $(this).attr('cn');
              $.ajax({
                type: "GET",
                url: "{{route('getPost')}}?id_pilih="+id_pilih,
                success: function (data) {
                  $('.id_post').html(data);
                }
              });
                    
        });
          // Monitoring biaya
          var count_monitoring = 1;
          $('#tambah_monitoring').click(function() {
            // var no_nota_atk = $("#no_nota_atk").val();
            var html_code = "<div class='row' id='row_monitoring" + count_monitoring + "'>";

            html_code += '<div class="col-md-3"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring" name="no_id[]" required></div></div>';

            html_code += '<div class="col-md-3"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring" name="keterangan[]" required></div></div>';
            html_code += '<div class="col-md-3"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring" name="ket2[]" required></div></div>';


            html_code += '<div class="col-md-3"><div class="form-group"><select name="id_post_center[]" id="id_post' + count_monitoring + '" cn="' + count_monitoring + '" class="form-control id_post  select"></select></div></div>';

            html_code += '<div class="col-md-2"><div class="form-group"><select name="id_satuan[]" class="form-control select satuan input_detail input_biaya" required><?php foreach ($satuan as $s) : ?><option value="<?= $s->id ?>"><?= $s->n ?></option> <?php endforeach; ?></select></div></div>';

            html_code += '<div class="col-md-2"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring qty_monitoring' + count_monitoring + '" rp_satuan="' + count_monitoring + '" name="qty[]" required></div></div>';


            html_code += '<div class="col-md-2"><div class="form-group"><input type="text" class="form-control  input_detail input_monitoring total_rp total_rp' + count_monitoring + '" name="ttl_rp[]" total_rp="' + count_monitoring + '" required></div></div>';

            html_code += '<div class="col-md-1"><button type="button" name="remove" data-row="row_monitoring' + count_monitoring + '" class="btn btn-danger btn-sm remove_monitoring">-</button></div>';


            html_code += "</div>";

            $('#detail_monitoring').append(html_code);
            $('.select').select2()
          });

          $(document).on('click', '.remove_monitoring', function() {
            var delete_row = $(this).data("row");
            $('#' + delete_row).remove();
          }); 

          // peralatan
          var count_monitoring = 1;
          $('#tambah_peralatan').click(function() {
            var html_code = "<div class='row' id='row_peralatan" + count_monitoring + "'>";

            html_code += '<div class="col-md-3"><div class="form-group"><label>No Id</label><input type="text" class="form-control input_detail input_monitoring" name="no_id[]" required></div></div>';

            html_code += '<div class="col-md-3"><div class="form-group"><label>Barang</label><input type="text" class="form-control input_detail input_monitoring" name="nm_barang[]" required></div></div>';


            html_code += '<div class="col-md-3"><div class="form-group"><label>Penanggung Jawab</label><select name="id_penanggung[]" class="form-control select2bs4 satuan input_detail input_peralatan" required><option>--Pilih penanggung jawab--</option><?php foreach ($nm_penanggung as $n) : ?><option value="<?= $n->id_penanggung ?>"><?= $n->nm_penanggung ?><?php endforeach; ?></option></select></div></div>';

            html_code += '<div class="col-md-2"><div class="form-group"><label>Lokasi</label><select name="id_lokasi[]" class="form-control select2bs4 satuan input_detail input_peralatan" required><?php foreach ($lokasi as $n) : ?><option value="<?= $n->id_lokasi ?>"><?= $n->nm_lokasi ?><?php endforeach; ?></option></select></div></div>';


            html_code += '<div class="col-md-2"><div class="form-group"><label>Satuan Barang</label><select name="id_satuan[]" class="form-control select satuan input_detail input_peralatan" required><?php foreach ($satuan as $s) : ?><option value="<?= $s->id ?>"><?= $s->n ?></option> <?php endforeach; ?></select></div></div>';

            html_code += '<div class="col-md-2"><div class="form-group"><label>Qty</label><input type="text" class="form-control input_detail input_monitoring qty_monitoring' + count_monitoring + '" rp_satuan="' + count_monitoring + '" name="qty[]" required></div></div>';


            html_code += '<div class="col-md-2"><div class="form-group"><label>Total Rp</label><input type="text" class="form-control  input_detail input_monitoring total_rp total_rp' + count_monitoring + '" name="ttl_rp[]" total_rp="' + count_monitoring + '" required></div></div>';

            html_code += ' <div class="col-md-1"><label>Aksi</label><button type="button" name="remove" data-row="row_peralatan' + count_monitoring + '" class="btn btn-danger btn-sm remove_peralatan">-</button></div>';

            html_code += '<div class="col-lg-5"><table class="table table-bordered table-sm" width="100%"><thead style="text-align: center;"><tr><th></th><th width="15%">Nama Kelompok</th><th width="15%">Umur</th><th width="70%">Barang</th> </tr></thead> <tbody><?php foreach ($peralatan as $a) : ?> <tr><td><input type="checkbox" name="id_kelompok[]" id="" value="<?= $a->id_kelompok ?>"></td><td><?= $a->nm_kelompok ?></td><td><?= $a->umur ?> <?= $a->satuan ?></td><td><?= $a->barang_kelompok ?></td></tr><?php endforeach ?></tbody></table></div> ';

            html_code += "</div>";

            $('#detail_peralatan').append(html_code);
            $('.select').select2()
          });

          $(document).on('click', '.remove_peralatan', function() {
            var delete_row = $(this).data("row");
            $('#' + delete_row).remove();
          });             

          // tambah stok
          var count = 1;
          $('#tambah_stok').click(function() {

            count = count + 1
            var html_code = "<div class='row' id='row_monitoring" + count + "'>";
              
          html_code += '<div class="col-md-3"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring" name="no_id[]" required></div></div>';

          html_code += '<div class="col-md-3"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring" name="keterangan[]" required></div></div>';


          html_code += '<div class="col-md-3"><div class="form-group"><select name="id_list_bahan[]" id="id_list_bahan'+count+'" detail="'+count+'" class="form-control select listBahan"></select></div></div>';

          html_code += '<div class="col-md-2"><div class="form-group"><input type="hidden" id="idSatuanResep'+count+'" readonly name="id_satuan[]" class="form-control input_detail input_stok"><input type="text" id="satuanResep'+count+'" readonly  class="form-control input_detail input_stok"></div></div>';

          html_code += '<div class="col-md-3"><div class="form-group"><select name="id_merk_bahan[]" id="id_merk_bahan'+count+'" class="form-control select satuan input_detail input_stok merkBahan" required><div class="km"></div></select></div></div>';
          html_code += '<div class="col-md-2"><div class="form-group"><input type="text" class="form-control input_detail input_monitoring qty_monitoring' + count + '" rp_satuan="' + count + '" name="qty[]" required></div></div>';


          html_code += '<div class="col-md-2"><div class="form-group"><input type="text" class="form-control  input_detail input_monitoring total_rp total_rp' + count + '" name="ttl_rp[]" total_rp="' + count + '" required></div></div>';

          html_code += ' <div class="col-md-1"><button type="button" name="remove" data-row="row_monitoring' + count + '" class="btn btn-danger btn-sm remove_stok">-</button></div>';
          
          
          html_code += "</div>";
          
          $('#detail_stok').append(html_code);
          $('.select').select2()
          loadBahan(count)
        });

        $(document).on('click', '.remove_stok', function() {
          var delete_row = $(this).data("row");
          $('#' + delete_row).remove();
        });

        $(".merkB").click(function (e) { 
            $('.tbhMerk').modal('hide')
        });

        loadBahan(1)
        
        function loadBahan(detail) {
          $("#id_list_bahan"+detail).load("{{route('getLbahan')}}", "data", function (response, status, request) {
          this; // dom element
          
        });
        }
        
        $(document).on('change', '.merkBahan', function(){
          var vMerk = $(this).val()
          if(vMerk == 'tbhM') {
            $('.tbhMerk').modal('show')
          }
        })

        $("#modalBahan").submit(function (e) { 
          e.preventDefault();
          var tbh_namaBahan = $("#tbh_namaBahan").val()
          var tbh_idSatuan = $("#tbh_idSatuan").val()
          var tbh_kategori = $("#tbh_kategori").val()

          $.ajax({
            type: "POST",
            url: "{{route('saveLbahan')}}",
            data: {
              nm_bahan : tbh_namaBahan,
              id_satuan : tbh_idSatuan,
              kategori : tbh_kategori,
              jp : 'Y',
              "_token" : "{{ csrf_token() }}"
            },
            success: function (data) {
              Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Tambah Bahan berhasil'
                    });

              $('#tbhBahan').modal('toggle');
              loadBahan(1)
            }
          });
        });

        $("#modalMerk").submit(function (e) { 
          e.preventDefault();
          var nm_merk = $("#nm_merk").val()
          var id_list_bahan = $("#id_list_bahan").val()

          $.ajax({
            type: "POST",
            url: "{{route('saveMbahan')}}",
            data: {
              nm_merk : nm_merk,
              id_list_bahan : id_list_bahan,
              "_token" : "{{ csrf_token() }}"
            },
            success: function (data) {
              Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Tambah Merk berhasil'
                    });

              $('#tbhMerk').modal('toggle');
              loadBahan(1)
            }
          });
        });

        function loadMerk(id_list_bahan,detail) {
          $.ajax({
            type: "GET",
            url: "{{route('getMerkBahan')}}?id_list_bahan="+id_list_bahan,

            success: function (d) {
              $("#id_merk_bahan"+detail).html(d);
              if(id_list_bahan == 'tbh') {
                $('.tbhBahan').modal('show')
              } else if(id_list_bahan == 0) {
                $(".merkLoop").hide()
              } 
            }
          });
        }

        $(document).on('change', '.listBahan', function(){
          var id_list_bahan = $(this).val()
          var detail = $(this).attr("detail");
         
          $.ajax({
            type: "GET",
            url: "{{route('getSatuanResep')}}?id_list_bahan="+id_list_bahan,
            dataType: "json",
            success: function (data) {
              $("#satuanResep"+detail).val(data.satuan)
              $("#idSatuanResep"+detail).val(data.id_satuan)
            }
          });
          loadMerk(id_list_bahan,detail)
          
        })
        // $(".listBahan").change(function (e) { 
          
          
        // });
          $("body").on("change", ".proyek", function() {
            // $('.barang').change(function(){
            var proyek = $(this).val();
            // alert(detail);
            $.ajax({
              url: "{{ route('getProjek') }}",
              method: "POST",
              data: {
                id_proyek: proyek,
                "_token": "{{ csrf_token() }}",
              },
              dataType: "json",
              success: function(data) {

                // alert(data.b_penyusutan);
                //   $('#cancel').modal('show');
                var bp = parseFloat(data.b_kredit);

                $('#ttlp').val(bp.toFixed(2));

                var debit = 0;

                $(".total_penyesuaian:not([disabled=disabled]").each(function() {
                  debit += parseFloat($(this).val());
                });
                $('.total').val(debit.toFixed(2));
              }
          });

          
          
      });

      $(document).on('change', '#tgl_peng', function() {
        var tgl_pen = $(this).val();
      const d = new Date(tgl_pen);
      let month = d.getMonth();
      let year = d.getFullYear();

      var bulan = month + 1
      var tahun = year

      $.ajax({
        url: "<?= route('getPenutup'); ?>?bulan="+bulan+"&tahun="+tahun,
        type: "GET",
        // dataType: "json",
        success: function(data) {
          $('#testing').text(data);
          if (data != '') {
            $('#id_pilih').attr('disabled', 'true');
            $('#metode').attr('disabled', 'true');
            $('.pen').hide();
          } else {
            $('#id_pilih').removeAttr('disabled', 'true');;
            $('#metode').removeAttr('disabled', 'true');;
            $('.pen').show();
          }
        }

      });
    });

    $('.modal').on('hidden.bs.modal', function() {
            //If there are any visible
            if ($(".modal:visible").length > 0) {
                //Slap the class on it (wait a moment for things to settle)
                setTimeout(function() {
                    $('body').addClass('modal-open');
                }, 200)
            }
        });
  });
    </script>
@endsection
