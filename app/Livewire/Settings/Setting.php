<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting as SettingModel;
class Setting extends Component
{
    public $company;
    public $ruc;
    public $country;
    public $city;
    public $address;
    public $phone;
    public $email;
    public $exchange_rate;

    protected $rules = [
        'company' => 'required',
        'ruc' => 'required|numeric',
        'country' => 'required',
        'city' => 'required',
        'address' => 'required',
        'phone' => 'required|numeric|min:9',
        'email' => 'required|email',
        'exchange_rate' => 'required|numeric',
    ];

    public function mount(){
        $setting = SettingModel::find(1);
        $this->company = $setting->company;
        $this->ruc = $setting->ruc;
        $this->country = $setting->country;
        $this->city = $setting->city;
        $this->address = $setting->address;
        $this->phone = $setting->phone;
        $this->email = $setting->email;
        $this->exchange_rate = $setting->exchange_rate;
    }

    public function save(){
        $this->validate();
        $setting = SettingModel::find(1);
        $setting->update([
            'company' => $this->company,
            'ruc' => $this->ruc,
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'exchange_rate' => $this->exchange_rate,
        ]);

        $this->dispatch('success',['label' => 'Se edito la información de la empresa.','btn' => 'Ir a configuración','route' => route('settings')]);
    }

    public function render()
    {
        return view('livewire.settings.setting');
    }
}
