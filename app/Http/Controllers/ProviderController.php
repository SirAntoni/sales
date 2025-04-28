<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProviderController extends Controller implements hasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update'), only:['show']),
        ];
    }
    public function index()
    {
       return view('providers.index');
    }
    public function create()
    {
        return view('providers.create');
    }
    public function show(string $id)
    {
        return view('providers.show',compact('id'));
    }
}
