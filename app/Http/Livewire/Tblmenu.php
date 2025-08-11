<?php

namespace App\Http\Livewire;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Tblmenu extends Component
{
    public $id_lokasi;
    public $keyword; // Add keyword property for search

    public function toggleActive($id_menu)
    {
        $menu = Menu::find($id_menu);
        if ($menu) {
            $menu->aktif = $menu->aktif == 'on' ? 'off' : 'on';
            $menu->save();
            $this->emit('refreshTable');  // Refresh table setelah toggle
            session()->flash('sukses', 'Status menu berhasil diubah!');
        }
    }

    public function addDistribusi($id_menu)
    {
        // Logika tambah distribusi (asumsi sederhana, bisa extend dengan modal)
        // Misalnya, buka modal via emit, atau langsung insert jika data dikirim
        // Contoh sederhana: Tambah distribusi default
        DB::table('tb_harga')->insert([
            'id_menu' => $id_menu,
            'id_distribusi' => 1,  // Default distribusi, ganti sesuai
            'harga' => 0,  // Default harga
        ]);
        $this->emit('refreshTable');
        session()->flash('sukses', 'Distribusi berhasil ditambahkan!');
    }


    public function render()
    {
        $menu = Menu::with(['harga.distribusi'])  // Eager load harga dan relasi distribusi
            ->leftJoin('tb_kategori', 'tb_menu.id_kategori', '=', 'tb_kategori.kd_kategori')  // Join kategori
            ->leftJoin('tb_handicap', 'tb_menu.id_handicap', '=', 'tb_handicap.id_handicap')  // Join handicap (untuk handicapId, handicap, point)
            ->leftJoin('tb_station', 'tb_menu.id_station', '=', 'tb_station.id_station')  // Join station
            ->select(
                'tb_menu.*',
                'tb_kategori.kategori',  // Field dari kategori
                'tb_handicap.id_handicap as handicapId',
                'tb_handicap.handicap',
                'tb_handicap.point',  // Field dari handicap
                'tb_station.nm_station'  // Field dari station
            )
            ->where('tb_menu.lokasi', $this->id_lokasi)
            ->when($this->keyword, function ($query) {
                $query->where(function ($query) {
                    $query->where('tb_menu.nm_menu', 'like', '%' . $this->keyword . '%')  // Search by keyword
                        ->orWhere('tb_menu.kd_menu', 'like', '%' . $this->keyword . '%');  // Search by kd_menu
                });
            })
            ->orderBy('tb_menu.id_menu', 'DESC')
            ->paginate(10);

        return view('livewire.tblmenu', [
            'menu' => $menu,
        ]);
    }
}
