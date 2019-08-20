<?php

namespace App\Http\Controllers;

use App\Fact;
use App\Source;
use Illuminate\Http\Request;

class SourcesFactsController extends Controller
{
   public function create(Source $source, Request $request) {
     Fact::create([
         'source_id' => $source->id,
         'claim' => $request['claim'],
     ]);
     return response('', 204);
   }
}
