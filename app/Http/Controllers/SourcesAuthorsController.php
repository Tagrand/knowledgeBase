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
        $source->authors()->attach($author);

        return response('', 204);
    }

    public function destroy(Source $source, Author $author)
    {
        $source->authors()->detach($author);

        return response('', 204);
    }
}
