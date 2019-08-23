<?php

namespace App\Http\Controllers;

use App\Issue;

class IssuesFactsController extends Controller
{
   public function index(Issue $issue) {
      return $issue->facts;
   }
}
