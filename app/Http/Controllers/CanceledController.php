<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CanceledController extends Controller
{
    public function index(){
        return view('canceled.index');
    }

    public function purchases(){
        return view('canceled.purchases');
    }
}
