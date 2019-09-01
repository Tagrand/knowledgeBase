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
        return Argument::create([
          'source_id' => $request['source_id'],
          'reason' => $request['reason'],
      ]);
    }
}
