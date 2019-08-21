<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function index()
    {
        return Author::all();
    }

    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        return Author::create([
          'first_name' => $validatedRequest['first_name'],
          'last_name' => $request['last_name'],
      ]);
    }
}
