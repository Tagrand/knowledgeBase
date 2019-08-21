<?php

namespace App\Http\Controllers;

use App\Source;

class SourcesAuthorsController extends Controller
{
    public function index(Source $source)
    {
        return $source->authors;
    }
}
