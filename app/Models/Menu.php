<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'tb_menu';
    protected $primaryKey = 'id_menu';
    protected $fillable = [
        'id_kategori',
        'id_station',
        'id_handicap',
        'kd_menu',
        'nm_menu',
        'tipe',
        'jenis',
        'lokasi',
        'image',
        'aktif',
        'tgl_sold'
    ];

    public function harga()
    {
        return $this->hasMany(Harga::class, 'id_menu', 'id_menu');
    }
}
