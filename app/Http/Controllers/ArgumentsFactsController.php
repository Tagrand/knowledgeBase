<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Argument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArgumentsFactsController extends Controller
{
    public function index(Argument $argument)
    {
        return $argument->factsWithSupport;
    }

    public function store(Argument $argument, Fact $fact, Request $request)
    {
        $validatedRequest = $request->validate(['is_supportive' => 'boolean']);

        $argument->facts()->attach($fact, $validatedRequest);

        return response('', 204);
    }

    public function update(Argument $argument, Fact $fact, Request $request)
    {
        $validatedRequest = $request->validate(['is_supportive' => 'boolean']);

        $argument->facts()->updateExistingPivot($fact->id, $validatedRequest);

        return response('', 204);
    }

    public function destroy(Argument $argument, Fact $fact) {
        $argument->facts()->detach($fact);

        return response('', 204);
    }
}
