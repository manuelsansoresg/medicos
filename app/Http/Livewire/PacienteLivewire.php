<?php

namespace App\Http\Livewire;

use App\Models\Paciente;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PacienteLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $isList          = '';
    public    $isDownload      = false;
    public    $isShowDownload;
    public    $isOriginSolicitud;
    public    $limit;

    public function mount($limit, $isList = false, $isShowDownload = true, $isOriginSolicitud = false)
    {
        $this->limit             = $limit;
        $this->isList            = $isList;
        $this->isShowDownload    = $isShowDownload;
        $this->isOriginSolicitud = $isOriginSolicitud;
        $this->isDownload        = User::getIsPermissionDownload();
    }
    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $pacientes = User::getUsersByRoles(['paciente'], $this->search, $this->limit, true);
        return view('livewire.paciente-livewire', compact('pacientes'));
    }
}
