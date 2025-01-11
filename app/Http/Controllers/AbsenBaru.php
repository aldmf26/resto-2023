<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AbsenBaru extends Controller
{
    public function index(Request $request)
    {
        $tgl = empty($request->tgl) ? date('Y-m-d') : $request->tgl;
        $data = [
            'tgl' => $tgl,
            'title' => 'Absen',
            'logout' => $request->session()->get('logout'),
        ];

        return view('absenBaru.index', $data);
    }

    public function get_absen(Request $request)
    {
        $data = [
            'tgl' => $request->tgl,
            'logout' => $request->session()->get('logout'),
            'karyawan' => DB::select("SELECT a.*, b.shift_id
            FROM tb_karyawan as a 
            left join (
                SELECT * FROM absennew where tgl = '$request->tgl'
            ) as b on a.id_karyawan = b.karyawan_id
            order by a.id_status ASC, a.nama ASC
            "),
            'shift' => DB::table('tb_shift')->get()
        ];

        return view('absenBaru.get_absen', $data);
    }
    public function save_absen_baru(Request $request)
    {
        DB::table('absennew')->where('karyawan_id', $request->id_karyawan)->where('tgl', $request->tgl)->delete();

        if ($request->id_shift == 0) {
            # code...
        } else {
            $data = [
                'karyawan_id' => $request->id_karyawan,
                'tgl' => $request->tgl,
                'shift_id' => $request->id_shift,
            ];
            DB::table('absennew')->insert($data);
        }
    }

    public function print_absen(Request $request)
    {
        $tgl1 = (int) date('d', strtotime($request->tgl1)); // Default ke 1
        $tgl2 = (int) date('d', strtotime($request->tgl2)); // Default ke 26

        $dates = range($tgl1, $tgl2);

        $data = [
            'title' => 'Print Absen',
            'tgl1' => $request->tgl1,
            'tgl2' => $request->tgl2,
            'logout' => $request->session()->get('logout'),
            'karyawan' => DB::select("SELECT a.id_karyawan, a.nama FROM tb_karyawan as a"),
            'dates' => $dates,
            'tahun' => date('Y', strtotime($request->tgl1)),
            'bulan' => date('m', strtotime($request->tgl1)),

        ];
        return view('absenBaru.print_absen', $data);
    }
    public function export_absen(Request $request)
    {
        $tgl1 = (int) date('d', strtotime($request->tgl1)); // Default ke 1
        $tgl2 = (int) date('d', strtotime($request->tgl2)); // Default ke 26

        $dates = range($tgl1, $tgl2);

        $data = [
            'title' => 'Export Absen',
            'tgl1' => $request->tgl1,
            'tgl2' => $request->tgl2,
            'logout' => $request->session()->get('logout'),
            'karyawan' => DB::select("SELECT a.id_karyawan, a.nama FROM tb_karyawan as a"),
            'dates' => $dates,
            'tahun' => date('Y', strtotime($request->tgl1)),


        ];
        return view('absenBaru.export', $data);
    }

    public function print_absen2(Request $request)
    {
        if (empty($request->tgl1)) {
            $tgl1 = date('Y-m-01');
            $tgl2 = date('Y-m-d');
        } else {
            $tgl1 = $request->tgl1;
            $tgl2 = $request->tgl2;
        }
        $karyawan = Http::get("https://ptagafood.com/api/absenBaru?tgl1=$tgl1&tgl2=$tgl2");
        $dt_karyawan = json_decode($karyawan, TRUE);

        $absen = Http::get("https://ptagafood.com/api/absenPrint?tgl1=$tgl1&tgl2=$tgl2",);
        $dt_absen = json_decode($absen, true);

        DB::table('absennew')->whereBetween('tgl', [$tgl1, $tgl2])->delete();

        foreach ($dt_absen['data']['absen'] as $a) {
            $data = [
                'karyawan_id' => $a['karyawan_id'],
                'tgl' => $a['tgl'],
                'shift_id' => $a['shift_id'],
            ];
            DB::table('absennew')->insert($data);
        }

        $data = [
            'title' => 'Absen',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'logout' => $request->session()->get('logout'),
            'karyawan' => $dt_karyawan['data']['karyawan'],
            'dates' => $dt_karyawan['data']['dates'],
            'tahun' => empty($request->tgl1) ? date('Y') : date('Y', strtotime($request->tgl1)),
            'bulan' => empty($request->tgl1) ? date('m') : date('m', strtotime($request->tgl1)),
        ];

        return view('absenBaru.index2', $data);
    }
}
