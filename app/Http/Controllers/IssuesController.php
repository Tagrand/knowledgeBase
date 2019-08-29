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
            'name' => 'required|string',
            'summary' => 'nullable|string'
        ]);

        return Issue::create($validatedRequest);
    }

    public function update(Issue $issue, Request $request)
    {
        $validatedRequest = $request->validate([
            'name' => 'string',
            'summary' => 'nullable|string'
        ]);

        $issue->update($validatedRequest);

        return $issue->fresh();
    }
}
