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
        $validatedData = $request->validate([
           'claim' => 'string|unique:facts',
        ]);

        $fact->update($validatedData);

        return $fact->fresh();
    }
}
