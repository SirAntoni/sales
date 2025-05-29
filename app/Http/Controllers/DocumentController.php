<?php

namespace App\Http\Controllers;

use Greenter\Model\Sale\Invoice;
use Greenter\Report\HtmlReport;
use Illuminate\Http\Request;
use Greenter\Report\PdfReport;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('documents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function download(string $path)
    {
        $fullPath = storage_path($path);
        return response()->download($fullPath);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('documents.show',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
