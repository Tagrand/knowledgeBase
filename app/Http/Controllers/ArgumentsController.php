<?php

namespace App\Http\Controllers;

use App\Argument;
use Illuminate\Http\Request;

class ArgumentsController extends Controller
{
    public function index()
    {
        return Argument::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reason' => 'required|string|unique:arguments',
            'source_id' => 'nullable|exists:sources,id',
            'summary' => 'nullable|string',
        ]);

        return Argument::create($validatedData);
    }

    public function update(Argument $argument, Request $request) {
        $validatedData = $request->validate([
            'reason' => 'string|unique:arguments',
            'summary' => 'nullable|string',
        ]);

        $argument->update($validatedData);

        return $argument->fresh();
    }
}
