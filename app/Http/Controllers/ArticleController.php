<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ArticleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('articles.create'), only:['create']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update'), only:['show']),
        ];
    }

    public function index()
    {
        return view('articles.index');
    }
    public function create()
    {
        return view('articles.create');
    }
    public function show(string $id)
    {
        return view('articles.show',compact('id'));
    }
}
