<?php

namespace App\Http\Controllers;

use App\Source;
use App\Argument;
use Illuminate\Http\Request;

class SourcesArgumentsController extends Controller
{
    public function index(Source $source)
    {
        return $source->arguments()->with('issues')->get();
    }

    public function store(Source $source, Request $request)
    {
        $validatedData = $request->validate([
            'reason' => 'required|string|unique:arguments',
            'summary' => 'nullable|string',
        ]);

        return Argument::create(
            array_merge($validatedData, ['source_id' => $source->id])
        );
    }
}
