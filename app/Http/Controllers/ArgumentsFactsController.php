<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Argument;
use Illuminate\Http\Request;

class ArgumentsFactsController extends Controller
{
    public function store(Argument $argument, Fact $fact, Request $request)
    {
        $argument->facts()->attach($fact, ['is_supportive' => $request['is_supportive']]);

        return response('', 200);
    }
}
