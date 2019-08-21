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
        return Author::create([
          'first_name' => $request['first_name'],
          'last_name' => $request['last_name'],
      ]);
    }
}
