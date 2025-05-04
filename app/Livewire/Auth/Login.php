<?php

namespace App\Livewire\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Log;

use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;

    public $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login(){

        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();


            $user = auth()->user();

            $home = [
                'dashboard',
                'sales',
                'purchases',
                'articles',
                'brands',
                'categories',
                'providers',
                'reports',
                'users',
                'settings',
                'kardex',
            ];

            foreach ($home as $module) {
                // Si el usuario tiene el permiso
                if ($user->can($module)) {
                    // Para dashboard la ruta es 'dashboard',
                    // para recursos se usa '.index'
                    $routeName = $module === 'dashboard'
                        ? 'dashboard'
                        : "{$module}.index";

                    // Asegurarse de que existe la ruta
                    if (Route::has($routeName)) {
                        return redirect()->route($routeName);
                    }
                }
            }

            return redirect()->intended('/');

        } else {
            session()->flash('error', 'Credenciales incorrectas.');
        }

    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
