<?php

namespace App\Http\Controllers;

use App\Issue;

class IssuesArgumentsController extends Controller
{
    public function index(Issue $issue) {
        return $issue->arguments()->with('source')->get();
    }
}
