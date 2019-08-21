<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Source;
use Illuminate\Http\Request;

class SourcesFactsController extends Controller
{
    public function index(Source $source)
    {
        return $source->facts;
    }

    public function store(Source $source, Request $request)
    {
        $validatedRequest = $request->validate([
           'claim' => 'required|string',
        ]);

        return Fact::create([
         'source_id' => $source->id,
         'claim' => $validatedRequest['claim'],
        ]);
    }
}
