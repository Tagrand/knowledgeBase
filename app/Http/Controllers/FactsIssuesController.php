<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Issue;
use Illuminate\Http\Request;

class FactsIssuesController extends Controller
{
    public function store(Fact $fact, Issue $issue, Request $request)
    {
        $fact->issues()->attach($issue);

        return response('', 200);
    }
}
