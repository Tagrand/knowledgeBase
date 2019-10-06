<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Source;
use Illuminate\Http\Request;

class SourcesFactsController extends Controller
{
    public function index(Source $source)
    {
        return $source->facts()->with('issues')->get();
    }

    public function store(Source $source, Request $request)
    {
        $validatedRequest = $request->validate([
           'claim' => 'required|string',
           'summary' => 'string',
           'image' => 'string',
        ]);

        return Fact::create(
            array_merge($validatedRequest, ['source_id' => $source->id])
        );
    }
}
