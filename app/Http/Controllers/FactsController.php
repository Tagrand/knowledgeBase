<?php

namespace App\Http\Controllers;

use App\Fact;
use Illuminate\Http\Request;

class FactsController extends Controller
{
    public function index()
    {
        return Fact::with(['source', 'issues'])->get();
    }

    public function update(Fact $fact, Request $request)
    {
        $validatedData = $request->validate([
           'claim' => 'string|unique:facts',
           'source_id' => 'integer|exists:sources,id',
        ]);

        $fact->update($validatedData);

        return $fact->fresh();
    }
}
