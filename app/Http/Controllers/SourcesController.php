<?php

namespace App\Http\Controllers;

use App\Source;
use Illuminate\Http\Request;

class SourcesController extends Controller
{
    public function index()
    {
        return Source::with(['authors'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'summary' => 'nullable|string',
            'link' => 'string',
        ]);

        return Source::create($validatedData);
    }

    public function update(Source $source, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string',
            'summary' => 'nullable|string',
        ]);

        $source->update($validatedData);

        return $source->fresh();
    }
}
