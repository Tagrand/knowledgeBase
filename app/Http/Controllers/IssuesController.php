<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    public function index()
    {
        return Issue::all();
    }

    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'name' => 'required',
        ]);
        
        return Issue::create([
           'name' => $validatedRequest['name'],
           'summary' => $request['summary'],
        ]);
    }
}
