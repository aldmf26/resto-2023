<?php

namespace App\Http\Controllers;

use App\Models\ApiInvoiceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiInvoiceController extends Controller
{
    function invoice(Request $r)
    {
        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;
        $id_distribusi = $r->id_distribusi;
        $id_lokasi = $r->id_lokasi;
        $invoice = ApiInvoiceModel::dataInvoice($id_lokasi, $id_distribusi, $tgl1, $tgl2);

        $response = [
            'status' => 'success',
            'message' => 'Data Invoice berhasil diambil',
            'data' => [
                'invoice' => $invoice,
            ],
        ];
        return response()->json($response);
    }
    function menu(Request $r)
    {
        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;
        $id_lokasi = $r->id_lokasi;
        $menu = ApiInvoiceModel::dataMenu($id_lokasi, $tgl1, $tgl2);

        $response = [
            'status' => 'success',
            'message' => 'Data Invoice berhasil diambil',
            'data' => [
                'menu' => $menu,
            ],
        ];
        return response()->json($response);
    }

    public function absen(Request $request)
    {
        if (empty($request->tgl1)) {
            $tgldate1 = date('Y-m-01');
            $tgldate2 = date('Y-m-d');
            $tgl1 = (int) date('d', strtotime($tgldate1)); // Default ke 1
            $tgl2 = (int) date('d', strtotime($tgldate2)); // Default ke 26
        } else {
            $tgl1 = (int) date('d', strtotime($request->tgl1)); // Default ke 1
            $tgl2 = (int) date('d', strtotime($request->tgl2)); // Default ke 26
        }


        $dates = range($tgl1, $tgl2);

        $response = [
            'title' => 'Print Absen',
            'tgl1' => $request->tgl1,
            'tgl2' => $request->tgl2,
            'karyawan' => DB::select("SELECT a.id_karyawan, a.nama FROM tb_karyawan as a"),
            'dates' => $dates,
            'tahun' => empty($request->tgl1) ? date('Y') : date('Y', strtotime($request->tgl1)),

        ];
        return response()->json($response);
    }
}
