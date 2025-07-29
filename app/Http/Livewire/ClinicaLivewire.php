<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Clinica;

class ClinicaLivewire extends Component
{

    public $clinicas;

    public function mount()
    {
        $this->clinicas = Clinica::all();
    }

    public function render()
    {
        return view('livewire.clinica-livewire', ['clinicas' => $this->clinicas]);
    }
}
