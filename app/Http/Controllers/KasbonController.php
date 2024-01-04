<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kasbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class KasbonController extends Controller
{
    public function index(Request $r)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user',$id_user)
        ->where('id_menu', 4)->first();
        if(empty($id_menu)) {
            return back();
        } else {
            $tgl1 = $r->tgl1 ?? date('Y-m-01');
            $tgl2 = $r->tgl2 ?? date('Y-m-t');
            $data = [
                'title' => 'Data Kasbon',
                'logout' => $r->session()->get('logout'),
                'kasbon' => Kasbon::whereBetween('tgl', [$tgl1,$tgl2])->orderBy('id_kasbon','desc')->get(),
                'karyawan' => Karyawan::all(),
                'tgl1' => $tgl1,
                'tgl2' => $tgl2,
            ];
    
            return view('kasbon.kasbon', $data);
        }
    }

    public function addKasbon(Request $r)
    {   
        $nm_karyawan = $r->nama;
        $nominal = $r->nominal;
        $tgl = $r->tgl;
        for ($i=0; $i < count($nm_karyawan) ; $i++) { 
            $cek = Kasbon::where([['tgl', $tgl],['nm_karyawan', $nm_karyawan[$i]],['nominal', $nominal[$i]]])->first();
            if(!$cek) {
                $data = [
                    'nm_karyawan' => $nm_karyawan[$i],
                    'nominal' => $nominal[$i],
                    'tgl' => $tgl,
                    'admin' => Auth::user()->nama,
                ];
                Kasbon::create($data);
            }
        }
        

       

        return redirect()->route('kasbon')->with('sukses', 'Berhasil tambah kasbon');
    }

    public function editKasbon(Request $request)
    {
        $data = [
            'nm_karyawan' => $request->nama,
            'nominal' => $request->nominal,
            'tgl' => $request->tgl,
            'admin' => Auth::user()->nama
        ];
    
        kasbon::where('id_kasbon',$request->id_kasbon)->update($data);

      
        return redirect()->route('kasbon')->with('sukses', 'Berhasil Ubah Data kasbon');
    }

    public function deleteKasbon(Request $request)
    {
        kasbon::where('id_kasbon',$request->id_kasbon)->delete();
        return redirect()->route('kasbon')->with('error', 'Berhasil hapus kasbon');
    }

    public function printKasbon(Request $r)
    {
        $dari = $r->tgl1;
        $sampai = $r->tgl2;
        // dd($sampai);
        $data = [
            'title' => 'Kasbon Prtint',
            'date' => date('m/d/Y'),
            'dari' => $dari,
            'sampai' => $sampai,
            'kasbon' => DB::select("SELECT *, SUM(nominal) as totalNominal from tb_kasbon WHERE tgl BETWEEN '$dari' AND '$sampai' GROUP BY nm_karyawan"),
            'sum' => Kasbon::whereBetween('tgl', [$dari,$sampai])->sum('nominal'),
        ];
        
        return view('kasbon.pdf', $data);
        // $pdf = PDF::loadView('kasbon.pdf', $data);

        // return $pdf->download('Data Kasbon.pdf');
    }
}
