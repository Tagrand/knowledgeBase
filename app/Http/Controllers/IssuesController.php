<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    public function index()
    {
        return Issue::all();
    }
}
