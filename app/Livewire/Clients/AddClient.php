<?php

namespace App\Livewire\Clients;

use App\Models\Department;
use Livewire\Component;
use App\Models\Client;
use Log;
use App\Models\Province;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class AddClient extends Component
{
    public $name;
    public $document_number;
    public $document_type;
    public $address;
    public $phone;
    public $email;
    public $departments;
    public $provinces = [];
    public $districts = [];

    public $departmentSelect = null;
    public $provinceSelect = null;
    public $districtSelect = null;

    public function mount(){
        $departments = DB::table('departments')->get();
        $this->departments = $departments;
    }

    public function updatedDepartmentSelect($value)
    {
        // Reiniciamos los selects dependientes
        $this->districtSelect = null;
        $this->provinceSelect = null;
        $this->districts = [];
        $this->provinces = DB::table('provinces')->where('department_id', $value)->get();

    }

    public function updatedProvinceSelect($value)
    {
        $this->districts = DB::table('districts')->where('province_id', $value)->get();
    }

    protected $rules = [
        'name' => 'required|string|min:3',
        'document_number' => 'required|numeric|min:3',
        'document_type' => 'required|string|min:2',
        'address' => 'nullable|string|min:3',
        'phone' => 'nullable|numeric|min:3',
        'email' => 'nullable|email',
        'departmentSelect' => 'required|numeric|min:1',
        'provinceSelect' => 'required|numeric|min:1',
        'districtSelect' => 'required|numeric|min:1'
    ];

    protected $validationAttributes = [
        'name' => 'nombre',
        'document_number' => 'documento',
        'document_type' => 'tipo de documento',
        'address' => 'dirección',
        'phone' => 'teléfono',
        'email' => 'correo',
        'departmentSelect' => 'departamento',
        'provinceSelect' => 'provincia',
        'districtSelect' => 'distrito'
    ];

    public function save(){
        $this->validate();
        Client::create([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'document_type' => $this->document_type,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'department_id' => $this->departmentSelect,
            'province_id' => $this->provinceSelect,
            'district_id' => $this->districtSelect
        ]);

        $this->dispatch('success',['label' => 'Se agrego el cliente con éxito.','btn' => 'Ir a clientes','route' => route('clients.index')]);
    }

    public function render()
    {

        return view('livewire.clients.add-client');
    }
}
