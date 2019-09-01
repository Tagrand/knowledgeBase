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
            'reason' => 'required|string',
        ]);

        return Argument::create([
          'source_id' => $request['source_id'],
          'reason' => $validatedData['reason'],
      ]);
    }
}
