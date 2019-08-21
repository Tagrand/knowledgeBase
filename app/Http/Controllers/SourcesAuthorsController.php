<?php

namespace App\Http\Controllers;

use App\Author;
use App\Source;

class SourcesAuthorsController extends Controller
{
    public function index(Source $source)
    {
        return $source->authors;
    }

    public function store(Source $source, Author $author)
    {
        $source->authors()->attach($source);

        return response('', 204);
    }
}
