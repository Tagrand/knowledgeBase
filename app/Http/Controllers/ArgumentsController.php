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
            'source_id' => 'nullable|exists:sources',
        ]);

        return Argument::create($validatedData);
    }
}
