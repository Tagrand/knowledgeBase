<?php

namespace Tests\Unit;

use App\Fact;
use App\Argument;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentFactTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_join_arguments_and_issues()
    {
        $argument = factory(Argument::class)->create();
        $fact = factory(Fact::class)->create();
        $argument->facts()->attach($fact);

        $this->assertDatabaseHas('argument_fact', [
            'argument_id' => $argument->id,
            'fact_id' => $fact->id,
        ]);
    }

    public function test_you_cannot_join_facts_and_issues_twice()
    {
        $this->expectException(QueryException::class);

        $argument = factory(Argument::class)->create();
        $issue = factory(Issue::class)->create();
        $argument->issues()->attach($issue);
        $argument->issues()->attach($issue);

        $this->assertTrue(true);
    }
}