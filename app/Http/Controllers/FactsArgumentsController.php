<?php

namespace App\Http\Controllers;

use App\Fact;

class FactsArgumentsController extends Controller
{
    public function index(Fact $fact)
    {
        return $fact->argumentsWithSupport;
    }
}
