<?php

namespace App\Exports;

use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class GajiExport implements FromView, WithStyles
{

    protected $tgl1, $tgl2, $karyawan;

    // Terima parameter melalui constructor
    public function __construct($tgl1, $tgl2, $karyawan)
    {
        $this->tgl1 = $tgl1;
        $this->tgl2 = $tgl2;
        $this->karyawan = $karyawan;
    }
    public function view(): View
    {
        $shift = DB::table('tb_shift')->get();
        $tgl1 = $this->tgl1;
        $tgl2 = $this->tgl2;
        $queryParts = [];
        $show = [];
        foreach ($shift as $s) {
            $queryParts[] = "COUNT(CASE WHEN a.shift_id = $s->id_shift THEN tgl END) AS $s->ket";
            $show[] = " e.$s->ket";
        }
        $query = implode(", ", $queryParts);
        $query_show = implode(", ",  $show);
        $ttl_show = implode(" + ",  $show);

        $gaji = DB::select("SELECT a.id_karyawan, a.nama, a.tgl_masuk, b.nm_status, c.nm_posisi, d.kategori, d.rp_gaji, f.kasbon,g.denda,
        $query_show , ($ttl_show) as ttl
        FROM tb_karyawan as a
        left join tb_status as b on b.id_status = a.id_status
        left join tb_posisi as c on c.id_posisi = a.id_posisi
        left join tb_gaji_baru as d on d.karyawan_id = a.id_karyawan
        
        left join (
            SELECT 
                a.karyawan_id, b.nama,
                $query
            FROM absennew as a
            left join tb_karyawan as b on b.id_karyawan = a.karyawan_id
            where a.tgl BETWEEN '$tgl1' and '$tgl2'
            GROUP BY  a.karyawan_id
        ) as e on e.karyawan_id = a.id_karyawan
        left join (
            SELECT f.id_karyawan , sum(f.nominal) as kasbon
            FROM tb_kasbon as f 
            where f.tgl BETWEEN '$tgl1' and '$tgl2'
        ) as f on f.id_karyawan = a.id_karyawan
        left join (
            SELECT f.id_karyawan , sum(f.nominal) as denda
            FROM tb_denda as f 
            where f.tgl BETWEEN '$tgl1' and '$tgl2'
        ) as g on g.id_karyawan = a.id_karyawan
        where a.id_posisi != 2
        order by  b.nm_status ASC , a.nama ASC;");

        $data = [
            'gaji' => $gaji,
            'shift' => $shift
        ];
        return view('gaji.excel', $data);
    }

    public function styles(Worksheet $sheet)
    {
        $ttl = $this->karyawan + 1;
        $ttl2 = $this->karyawan + 2;
        // Tambahkan border untuk range tertentu
        $sheet->getStyle("A1:O1")->applyFromArray([
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ]);
        $sheet->getStyle("A2:O$ttl")->applyFromArray([
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ]);
        $sheet->getStyle("A$ttl2:O$ttl2")->applyFromArray([
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ]);

        return $sheet;
    }
}
