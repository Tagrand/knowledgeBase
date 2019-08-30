<?php

namespace App\Http\Controllers;

use App\Fact;
use Illuminate\Http\Request;

class FactsController extends Controller
{
    public function index()
    {
        return Fact::all();
    }

    public function update(Fact $fact, Request $request)
    {
        $fact->update([
            'claim' => $request['claim'],
        ]);

        return response('', 200);
    }
}
