<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Argument;
use Illuminate\Http\Request;

class ArgumentsFactsController extends Controller
{
    public function store(Argument $argument, Fact $fact, Request $request)
    {
        $validatedRequest = $request->validate(['is_supportive' => 'boolean']);

        $argument->facts()->attach($fact, $validatedRequest);

        return response('', 204);
    }
}
