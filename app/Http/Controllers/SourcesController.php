<?php

namespace App\Http\Controllers;

use App\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SourcesController extends Controller
{
    public function index() {
        return response(Source::all(), 200);
    }

    public function create(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);
        
        Source::create($validatedData);
        return response('', 204); 
    }
}
