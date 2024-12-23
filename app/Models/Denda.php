<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;
    protected $table = 'tb_denda';
    protected $fillable = [
        'id_karyawan',
        'alasan',
        'nominal',
        'tgl',
        'id_lokasi',
        'admin'
    ];
}
