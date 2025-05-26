<?php

namespace App\Livewire\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Log;
use App\Services\UserRedirectService;

use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;

    public $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login(UserRedirectService $redirectService){

        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();


            $user = auth()->user();

            $routeName = $redirectService->getHomeRouteForUser($user);

            return redirect()->route($routeName);

        } else {
            session()->flash('error', 'Credenciales incorrectas.');
        }

    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
