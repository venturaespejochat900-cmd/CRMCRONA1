<?php

namespace App\Http\Livewire;

use App\Models\CabeceraPedidoClienteModel;
use Livewire\Component;
use Livewire\WithPagination;

class PedidosDireccion extends Component 
{
    use WithPagination;

    public $model = CabeceraPedidoClienteModel::class;
    public $post;
    public $direccion = "0";

    public function updatedDireccion()
    {
        $this->resetPage(); // Resetear la paginación al cambiar la dirección
    }

    public function render()
    {
        $query = CabeceraPedidoClienteModel::query()
            ->where('CodigoCliente', '=', $this->post)
            ->where('CodigoEmpresa', '=', session('codigoEmpresa'));

        $query->where("DomicilioEnvio", $this->direccion);

        return view('livewire.pedidos-direccion', [
            'pedidos' => $query->paginate(13)
        ]);
    }
}
