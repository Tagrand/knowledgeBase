<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Issue;

class FactsIssuesController extends Controller
{
    public function index(Fact $fact)
    {
        return $fact->issues;
    }

    public function store(Fact $fact, Issue $issue)
    {
        $fact->issues()->attach($issue);

        return response('', 204);
    }

    public function destroy(Fact $fact, Issue $issue) {
        $fact->issues()->detach($issue);

        return response('', 204);
    }
}
