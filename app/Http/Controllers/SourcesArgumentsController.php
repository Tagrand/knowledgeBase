<?php

namespace App\Http\Controllers;

use App\Source;

class SourcesArgumentsController extends Controller
{
    public function index(Source $source)
    {
        return $source->arguments()->with('issues')->get();
    }
}
