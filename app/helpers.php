<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('tanggal')) {
  function tanggal($tgl)
  {
    $date = explode("-", $tgl);

    $bln  = $date[1];

    switch ($bln) {
      case '01':
        $bulan = "Januari";
        break;
      case '02':
        $bulan = "Februari";
        break;
      case '03':
        $bulan = "Maret";
        break;
      case '04':
        $bulan = "April";
        break;
      case '05':
        $bulan = "Mei";
        break;
      case '06':
        $bulan = "Juni";
        break;
      case '07':
        $bulan = "Juli";
        break;
      case '08':
        $bulan = "Agustus";
        break;
      case '09':
        $bulan = "September";
        break;
      case '10':
        $bulan = "Oktober";
        break;
      case '11':
        $bulan = "November";
        break;
      case '12':
        $bulan = "Desember";
        break;
    }
    $tanggal = $date[2];
    $tahun   = $date[0];

    $strTanggal = "$tanggal $bulan $tahun";
    return $strTanggal;
  }
}

class PointQuery
{
  public static function getServer($id_lokasi_tkm, $tgl1, $tgl2)
  {
    return DB::select("SELECT 
                a.point, 
                a.nama, 
                a.tgl_masuk, 
                kpi.ttl, 
                kasbon.kasbon, 
                b.rp_m, 
                sum(l.qty_m) AS qty_m, 
                sum(l.qty_e) AS qty_e, 
                sum(l.qty_sp) AS qty_sp, 
                b.rp_e, 
                b.rp_sp, 
                b.komisi, 
                c.kom, 
                c.nm_karyawan as karyawan_majo ,
                y.denda
              FROM 
                tb_karyawan AS a 
                left join tb_gaji AS b ON b.id_karyawan = a.id_karyawan 
                LEFT JOIN (
                  SELECT 
                    c.id_karyawan, 
                    c.status, 
                    c.id_lokasi, 
                    if(
                      c.status = 'M', 
                      COUNT(c.status), 
                      0
                    ) AS qty_m, 
                    if(
                      c.status = 'E', 
                      COUNT(c.status), 
                      0
                    ) AS qty_e, 
                    if(
                      c.status = 'SP', 
                      COUNT(c.status), 
                      0
                    ) AS qty_sp, 
                    if(
                      c.status = 'OFF', 
                      COUNT(c.status), 
                      0
                    ) AS qty_off 
                  FROM 
                    tb_absen AS c 
                  WHERE   
                    c.tgl BETWEEN '$tgl1' AND '$tgl2' 
                    and c.id_lokasi = '$id_lokasi_tkm' 
                    and c.status != 'OFF' 
                  GROUP BY 
                    c.id_karyawan, 
                    c.status
                ) AS l ON l.id_karyawan = a.id_karyawan 
                LEFT JOIN (
                  SELECT 
                    a.admin, 
                    SUM(
                      if(a.voucher = 'Y', 0, a.hrg)
                    ) AS komisi 
                  FROM 
                    view_summary_server AS a 
                    LEFT JOIN tb_karyawan as b on a.admin = b.nama 
                  WHERE 
                    a.tgl BETWEEN '$tgl1' 
                    AND '$tgl2' 
                    and a.id_lokasi = '$id_lokasi_tkm' 
                    AND b.point = 'Y' 
                  GROUP BY 
                    a.admin
                ) AS b ON b.admin = a.nama 
                LEFT JOIN (
                  SELECT 
                    SUM(a.komisi) AS kom, 
                    b.id_karyawan, 
                    b.nm_karyawan 
                  FROM 
                    komisi AS a 
                    LEFT JOIN tb_karyawan_majo AS b ON b.kd_karyawan = a.id_kry 
                  WHERE 
                    a.tgl BETWEEN '$tgl1' 
                    AND '$tgl2' 
                  GROUP BY 
                    a.id_kry
                ) AS c ON c.id_karyawan = a.id_karyawan 
                LEFT JOIN (
                  SELECT 
                    a.id_karyawan, 
                    count(a.id_karyawan) as ttl 
                  FROM 
                    tb_denda_kpi as a 
                  WHERE 
                    a.tgl BETWEEN '$tgl1' 
                    AND '$tgl2' 
                  GROUP BY 
                    a.id_karyawan
                ) kpi ON a.id_karyawan = kpi.id_karyawan 
                LEFT JOIN
                (
                    SELECT a.nm_karyawan, sum(a.nominal) as kasbon 
                    FROM tb_kasbon as a
                    WHERE 
                    a.tgl BETWEEN '$tgl1' 
                    AND '$tgl2'
                    GROUP BY a.nm_karyawan
                ) as kasbon ON kasbon.nm_karyawan = a.nama
                Left JOIN (
                SELECT y.nama , sum(y.nominal) as denda FROM tb_denda as y where y.tgl between '$tgl1' AND '$tgl2' group by y.nama
                ) as y on y.nama = a.nama
              WHERE 
                a.tgl_masuk <= '$tgl2' 
                and l.id_lokasi = '$id_lokasi_tkm' 
                and a.id_status = '2' 
              group by 
                a.id_karyawan
                Order by a.point DESC 
        ");
  }

  public static function getService($id_lokasi, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(if(tb_transaksi.total_orderan - discount - voucher < 0 ,0,tb_transaksi.total_orderan - discount - voucher)) as total FROM `tb_transaksi`
        LEFT JOIN(SELECT tb_order2.no_order2 as no_order, tb_order2.id_distribusi as id_distribusi FROM tb_order2 GROUP BY tb_order2.no_order2) dt_order ON tb_transaksi.no_order = dt_order.no_order
        WHERE tb_transaksi.id_lokasi = '$id_lokasi' and  dt_order.id_distribusi != '2' AND tb_transaksi.tgl_transaksi >= '$tgl1' AND tb_transaksi.tgl_transaksi <= '$tgl2'");
  }

  public static function getServiceServer($tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(if(tb_transaksi.total_orderan - discount - voucher < 0 ,0,tb_transaksi.total_orderan - discount - voucher)) as total FROM `tb_transaksi`
        LEFT JOIN(SELECT tb_order2.no_order2 as no_order, tb_order2.id_distribusi as id_distribusi FROM tb_order2 GROUP BY tb_order2.no_order2) dt_order ON tb_transaksi.no_order = dt_order.no_order
        WHERE dt_order.id_distribusi != '2' AND tb_transaksi.tgl_transaksi >= '$tgl1' AND tb_transaksi.tgl_transaksi <= '$tgl2'");
  }

  public static function getMasak($id_lokasi, $tgl1, $tgl2)
  {
    $lamaMenit = DB::table('tb_menit')->where('id_lokasi', $id_lokasi)->first();
    return DB::select("SELECT a.id_karyawan,a.nama,b.rp_m, sum(l.qty_m) AS qty_m, sum(l.qty_e) AS qty_e, sum(l.qty_sp) AS qty_sp,e.point_gagal,f.point_berhasil, b.rp_e, b.rp_sp
        FROM tb_karyawan AS a
        left join tb_gaji AS b ON b.id_karyawan = a.id_karyawan
        LEFT JOIN (
        SELECT c.id_karyawan,  c.status, c.id_lokasi,
        if(c.status = 'M', COUNT(c.status), 0) AS qty_m,
        if(c.status = 'E', COUNT(c.status), 0) AS qty_e,
        if(c.status = 'SP', COUNT(c.status), 0) AS qty_sp,
        if(c.status = 'OFF', COUNT(c.status), 0) AS qty_off
        FROM tb_absen AS c 
        WHERE c.tgl BETWEEN '$tgl1' AND '$tgl2' and c.id_lokasi = '$id_lokasi' and c.status != 'OFF'
        GROUP BY c.id_karyawan, c.status
        ) AS l ON l.id_karyawan = a.id_karyawan
        
        LEFT JOIN (
        SELECT koki, SUM(nilai_koki) as point_gagal FROM view_point2 
        WHERE tgl BETWEEN '$tgl1' AND '$tgl2' AND lama_masak > $lamaMenit->menit and id_lokasi = '$id_lokasi'
        GROUP BY koki , id_lokasi
        )e ON a.id_karyawan = e.koki
        
        LEFT JOIN (
            SELECT koki, SUM(nilai_koki) as point_berhasil FROM view_point2  
            WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND lama_masak <= $lamaMenit->menit and id_lokasi = '$id_lokasi'
            GROUP BY koki , id_lokasi
        )f ON a.id_karyawan = f.koki
            WHERE a.id_status = '1' and a.tgl_masuk <= '$tgl2' and l.id_lokasi ='$id_lokasi' and a.id_posisi not in ('3','2') and a.point =  'Y'
            group by a.id_karyawan
        ");
  }

  public static function getAbsen($id_lokasi, $tgl1, $tgl2)
  {
    $lamaMenit = DB::table('tb_menit')->where('id_lokasi', $id_lokasi)->first();
    return DB::select("SELECT x.kasbon,posisi.nm_posisi, y.denda, a.nama,a.tgl_masuk,a.point,b.rp_m, sum(l.qty_m) AS qty_m, sum(l.qty_e) AS qty_e, sum(l.qty_sp) AS qty_sp,e.point_gagal,f.point_berhasil, b.rp_e, b.rp_sp, z.nominal_bonus
        FROM tb_karyawan AS a
        left join tb_gaji AS b ON b.id_karyawan = a.id_karyawan
        JOIN tb_posisi as posisi ON a.id_posisi = posisi.id_posisi
        LEFT JOIN (
        SELECT c.id_karyawan,  c.status, c.id_lokasi,
        if(c.status = 'M', COUNT(c.status), 0) AS qty_m,
        if(c.status = 'E', COUNT(c.status), 0) AS qty_e,
        if(c.status = 'SP', COUNT(c.status), 0) AS qty_sp,
        if(c.status = 'OFF', COUNT(c.status), 0) AS qty_off
        FROM tb_absen AS c 
        WHERE c.tgl BETWEEN '$tgl1' AND '$tgl2' and c.id_lokasi = '$id_lokasi' and c.status != 'OFF'
        GROUP BY c.id_karyawan, c.status
        ) AS l ON l.id_karyawan = a.id_karyawan
        
        LEFT JOIN (
        SELECT koki, SUM(nilai_koki) as point_gagal FROM view_point2 
        WHERE tgl BETWEEN '$tgl1' AND '$tgl2' AND lama_masak > $lamaMenit->menit and id_lokasi = '$id_lokasi'
        GROUP BY koki , id_lokasi
        )e ON a.id_karyawan = e.koki
        
        Left JOIN (
        SELECT y.nama , sum(y.nominal) as denda FROM tb_denda as y where y.tgl between '$tgl1' AND '$tgl2' group by y.nama
        ) as y on y.nama = a.nama
        
        Left JOIN (
        SELECT x.nm_karyawan , sum(x.nominal) as kasbon FROM tb_kasbon as x where x.tgl between '$tgl1' AND '$tgl2' group by x.nm_karyawan
        ) as x on x.nm_karyawan = a.nama

        Left JOIN (
        SELECT y.id_karyawan, sum(y.nominal) as nominal_bonus  FROM bonus as y where y.tgl between '$tgl1' AND '$tgl2' group by x.id_karyawan
        ) as z on z.id_karyawan = a.id_karyawan
        
        LEFT JOIN (
            SELECT koki, SUM(nilai_koki) as point_berhasil FROM view_point2  
            WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND lama_masak <= $lamaMenit->menit and id_lokasi = '$id_lokasi'
            GROUP BY koki , id_lokasi
        )f ON a.id_karyawan = f.koki
            WHERE a.id_status = '1' and a.tgl_masuk <= '$tgl2' and l.id_lokasi ='$id_lokasi' and a.id_posisi not in ('3','2')
            group by a.id_karyawan
        ");
  }

  public static function getTotalGojek($id_lokasi, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(tb_transaksi.total_orderan - discount - voucher) as total FROM `tb_transaksi`
        LEFT JOIN(SELECT tb_order2.no_order2 as no_order, tb_order2.id_distribusi as id_distribusi FROM tb_order2 GROUP BY tb_order2.no_order2) dt_order ON tb_transaksi.no_order = dt_order.no_order
        WHERE id_lokasi = $id_lokasi AND dt_order.id_distribusi = 2 AND tb_transaksi.tgl_transaksi >= '$tgl1' AND tb_transaksi.tgl_transaksi <= '$tgl2'");
  }

  public static function getTotalNotGojek($loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(if(tb_transaksi.total_orderan - discount - voucher < 0 ,0,tb_transaksi.total_orderan - discount - voucher)) as total FROM `tb_transaksi`
        LEFT JOIN(SELECT tb_order2.no_order2 as no_order, tb_order2.id_distribusi as id_distribusi FROM tb_order2 GROUP BY tb_order2.no_order2) dt_order ON tb_transaksi.no_order = dt_order.no_order
        WHERE id_lokasi = $loc AND dt_order.id_distribusi != 2 AND tb_transaksi.tgl_transaksi >= '$tgl1' AND tb_transaksi.tgl_transaksi <= '$tgl2'");
  }

  public static function jml_telat($loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(qty) AS jml_telat FROM view_koki_masak WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND id_lokasi = $loc AND menit_bagi > 25");
  }

  public static function jml_telat20($loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(qty) AS jml_telat FROM view_koki_masak WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND id_lokasi = $loc AND menit_bagi > 20");
  }

  public static function jml_ontime($loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(qty) AS jml_ontime FROM view_koki_masak WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND id_lokasi = $loc AND menit_bagi <= 25");
  }

  public static function majo($distribusi, $loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(a.bayar) AS bayar_majo
        FROM tb_invoice AS a
        WHERE a.tgl_jam BETWEEN '$tgl1' AND '$tgl2' and a.lokasi = '$loc' and a.id_distribusi = '$distribusi'");
  }

  public static function dp($loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT SUM(a.jumlah) AS jumlah_dp
        FROM tb_dp AS a
        WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' and a.id_lokasi = '$loc'");
  }

  public static function transaksi($loc, $tgl1, $tgl2)
  {
    return DB::selectOne("SELECT COUNT(a.no_order) AS ttl_invoice,g.orang, SUM(a.discount) as discount, SUM(a.voucher) as voucher, sum(if(total_bayar = 0 ,0,a.round)) as rounding, a.id_lokasi, 
            SUM(a.total_orderan) AS rp, d.unit, a.no_order, sum(a.dp) as dp, sum(a.gosen) as gosend, sum(a.service) as ser, sum(a.tax) as tax,f.qty_void, f.void,
            SUM(a.cash) as cash, SUM(a.d_bca) as d_bca, SUM(a.k_bca) as k_bca, SUM(a.d_mandiri) as d_mandiri, SUM(a.k_mandiri) as k_mandiri,SUM(a.d_bri) as d_bri,SUM(a.k_bri) as k_bri, SUM(total_bayar) as total_bayar
        
            FROM tb_transaksi AS a
        
            LEFT JOIN(
            SELECT SUM(b.qty) AS unit , b.no_order, b.id_lokasi
            FROM tb_order AS b
            WHERE b.tgl BETWEEN '$tgl1' AND '$tgl2' AND b.id_lokasi = '$loc' AND b.void = 0
            GROUP BY b.id_lokasi
            )AS d ON d.id_lokasi = a.id_lokasi
        
            LEFT JOIN(
            SELECT SUM(e.void) AS void , COUNT(e.void) AS qty_void, e.no_order, e.id_lokasi
            FROM tb_order AS e
            WHERE e.tgl BETWEEN '$tgl1' AND '$tgl2' AND e.id_lokasi = '$loc' AND e.void != '0'
            GROUP BY e.id_lokasi
            )AS f ON f.id_lokasi = a.id_lokasi

            LEFT JOIN(
              SELECT no_order, id_lokasi, SUM(total_orang) AS orang
              FROM (
                  SELECT no_order, id_lokasi, orang AS total_orang
                  FROM `tb_order`
                  WHERE tgl BETWEEN '$tgl1' AND '$tgl2'
                  AND id_lokasi = $loc
                  GROUP BY no_order, id_lokasi, orang
              ) AS subquery
              GROUP BY id_lokasi
            ) as g ON a.id_lokasi = g.id_lokasi
        
        
            where a.tgl_transaksi BETWEEN '$tgl1' AND '$tgl2' and a.id_lokasi = '$loc'
            GROUP BY a.id_lokasi");
  }

  public static function kategori($loc, $tgl1, $tgl2)
  {
    return DB::select("SELECT b.nm_menu, c.kategori ,sum(e.harga2) as hargaT, sum(a.qty) AS qty
            FROM tb_order AS a 
            LEFT JOIN view_menu2 AS b ON b.id_harga = a.id_harga
            left join tb_kategori as c on c.kd_kategori = b.id_kategori
        
            left join(select d.id_harga, d.id_order, (d.harga * d.qty) as harga2 from tb_order as d 
            WHERE d.tgl BETWEEN '$tgl1' AND '$tgl2' and d.id_lokasi = '$loc' and d.id_distribusi = '1'
            group by d.id_order) as e on e.id_order = a.id_order           
        
            WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' and a.id_lokasi = '$loc' and a.id_distribusi = '1' 
            GROUP BY b.id_kategori");
  }

  public static function gojek($loc, $tgl1, $tgl2)
  {
    return DB::select("SELECT b.nm_menu, c.kategori, sum(e.harga2) as harga, sum(a.qty) AS qty
        FROM tb_order AS a 
        LEFT JOIN view_menu2 AS b ON b.id_harga = a.id_harga
        left join tb_kategori as c on c.kd_kategori = b.id_kategori
        left join(select d.id_harga, d.id_order, (d.harga * d.qty) as harga2 from tb_order as d 
        WHERE d.tgl BETWEEN '$tgl1' AND '$tgl2' and d.id_lokasi = '$loc' and d.id_distribusi = '2'
        group by d.id_order) as e on e.id_order = a.id_order  
        WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' and a.id_lokasi = '$loc' and a.id_distribusi = '2'
        GROUP BY b.id_kategori");
  }

  public static function void($loc, $tgl1, $tgl2)
  {
    return DB::select("SELECT c.kategori,b.nm_menu,sum(a.void) as void, sum(a.harga) as harga FROM `tb_order` as a 
            LEFT JOIN view_menu2 as b on a.id_harga = b.id_harga
            left join tb_kategori as c on b.id_kategori = c.kd_kategori
            WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' AND a.void = 1 AND id_lokasi = '$loc'
            GROUP BY c.kd_kategori");
  }
}

class Server
{
  public static function gaji_server($tgl1, $tgl2)
  {
    return DB::select("SELECT a.tgl_masuk, a.nama, sum(c.qty_m) as m, sum(c.qty_e) as e, sum(c.qty_sp) as sp, sum(c.qty_off) as off,  b.rp_e, b.rp_m, b.rp_sp, b.g_bulanan,
   d.kasbon, e.denda, a.point, f.nm_posisi, g.nominal_bonus
   FROM tb_karyawan as a 
   left join tb_gaji as b on b.id_karyawan =  a.id_karyawan
   left join tb_posisi as f on f.id_posisi =  a.id_posisi
   left join (
     SELECT c.id_karyawan, c.status, c.id_lokasi, 
       if(c.status = 'M', COUNT(c.status),0) AS qty_m,
       if(c.status = 'E', COUNT(c.status),0) AS qty_e,
       if(c.status = 'SP', COUNT(c.status),0) AS qty_sp,
       if(c.status = 'OFF', COUNT(c.status),0) AS qty_off
       FROM tb_absen as c 
       WHERE c.tgl BETWEEN '$tgl1' AND '$tgl2' and c.status != 'OFF' 
       GROUP BY c.id_karyawan,  c.status
   ) as c on c.id_karyawan = a.id_karyawan
   LEFT JOIN
   (
    SELECT d.nm_karyawan, sum(d.nominal) as kasbon 
    FROM tb_kasbon as d
    WHERE d.tgl BETWEEN '$tgl1' AND '$tgl2'
    GROUP BY d.nm_karyawan
   ) as d ON d.nm_karyawan = a.nama
   
   Left JOIN (
   SELECT e.nama , sum(e.nominal) as denda FROM tb_denda as e where e.tgl between '$tgl1' AND '$tgl2' group by e.nama
   ) as e on e.nama = a.nama
   Left JOIN (
   SELECT f.id_karyawan , sum(f.nominal) as nominal_bonus FROM bonus as f where f.tgl between '$tgl1' AND '$tgl2' group by f.id_karyawan
   ) as g on g.id_karyawan = a.id_karyawan
   
   where a.id_status = 2
   group by a.id_karyawan;");
  }



  public static function komstk($tgl1, $tgl2)
  {
    return DB::select("SELECT b.komisi, sum(a.total) as total, sum(a.total * (b.komisi/100)) as komisi_bagi, a.lokasi
    FROM tb_pembelian as a 
    left join tb_produk as b on b.id_produk = a.id_produk
    WHERE b.komisi != '0' and a.tanggal between '$tgl1' and '$tgl2' 
    group by b.komisi , a.lokasi;");
  }

  public static function penjualan_stk($id_lokasi, $tgl1, $tgl2)
  {
    return DB::select("SELECT b.nm_produk, sum(a.jumlah) as jumlah, b.komisi, sum(a.total) as total, a.lokasi 
    FROM tb_pembelian as a 
    left join tb_produk as b on b.id_produk = a.id_produk 
    where a.tanggal BETWEEN '$tgl1' and '$tgl2' and b.komisi != '0' and a.lokasi = '$id_lokasi'
    group by a.id_produk");
  }
}
