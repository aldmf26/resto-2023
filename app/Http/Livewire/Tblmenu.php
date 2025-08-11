<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Tblmenu extends Component
{
    public $id_lokasi;

    public function render()
    {
        return view('livewire.tblmenu');
    }
}
