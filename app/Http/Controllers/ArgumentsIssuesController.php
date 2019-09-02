<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Argument;

class ArgumentsIssuesController extends Controller
{
    public function index(Argument $argument) {
       return $argument->issues;
    }

    public function store(Argument $argument, Issue $issue) {
        $argument->issues()->attach($issue);

        return response('', 204);
    }
}
