<?php

namespace App\Http\Controllers;

use App\Argument;
use Illuminate\Http\Request;

class ArgumentsIssuesController extends Controller
{
    public function index(Argument $argument) {
       return $argument->issues;
    }
}
