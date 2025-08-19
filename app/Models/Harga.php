<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;
    protected $table = 'tb_harga';
    protected $primaryKey = 'id_harga';
    protected $fillable = [
        'id_distribusi',
        'harga',
        'id_menu'
    ];

    // public function distribusi()
    // {
    //     return $this->belongsTo(Distribusi::class, 'id_distribusi', 'id_distribusi');
    // }
}
