<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VoucherController extends Controller implements hasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update'), only:['show']),
        ];
    }

    public function index()
    {
        return view('vouchers.index');
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function show(string $id)
    {
        return view('vouchers.show',compact('id'));
    }
}
