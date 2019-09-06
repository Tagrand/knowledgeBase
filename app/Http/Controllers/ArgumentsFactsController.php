<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Argument;

class ArgumentsFactsController extends Controller
{
    public function store(Argument $argument, Fact $fact)
    {
        $argument->facts()->attach($fact);

        return response('', 200);
    }
}
