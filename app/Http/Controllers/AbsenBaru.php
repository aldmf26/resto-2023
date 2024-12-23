<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function openAbsen(Request $request)
    {
        $data = [
            'tgl' => $request->tgl,
        ];
        DB::table('absennew')->where('tgl', $request->tgl)->delete();
    }
}
