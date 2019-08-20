<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Source;
use Illuminate\Http\Request;

class SourcesFactsController extends Controller
{
    public function create(Source $source, Request $request)
    {
        $validatedRequest = $request->validate([
           'claim' => 'required|string',
        ]);
        Fact::create([
         'source_id' => $source->id,
         'claim' => $validatedRequest['claim'],
        ]);
        
        return response('', 204);
    }
}
