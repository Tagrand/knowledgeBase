<?php

namespace App\Http\Controllers;

use App\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SourcesController extends Controller
{
    public function index()
    {
        return response(Source::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'summary' => 'nullable|string',
        ]);

        return Source::create($validatedData);
    }

    public function update(Source $source, Request $request) {
        $validatedData = $request->validate([
            'name' => 'string',
            'summary' => 'nullable|string',
        ]);

        $source->update([
            'name' => $request['name'],
            'summary' => $validatedData['summary'],
        ]);

        return $source->fresh();
    }
}
