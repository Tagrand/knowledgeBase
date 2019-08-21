<?php

namespace App\Http\Controllers;

use App\Author;

class AuthorsController extends Controller
{
    public function index()
    {
        return Author::all();
    }
}
