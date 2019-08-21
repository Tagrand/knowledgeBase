<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'last_name' => [
                'required',
                'string',
                 Rule::unique('authors')->where(function ($query) use ($request) {
                     return $query
                        ->where('first_name', $request->first_name)
                        ->where('last_name', $request->last_name);
                 }),
            ],
        ]);

        return Author::create([
          'first_name' => $validatedRequest['first_name'],
          'last_name' => $request['last_name'],
      ]);
    }
}
