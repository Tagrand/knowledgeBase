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

    public function test_you_can_join_arguments_and_facts()
    {
        $argument = factory(Argument::class)->create();
        $fact = factory(Fact::class)->create();
        $argument->facts()->attach($fact, ['is_supportive' => false]);

        $this->assertDatabaseHas('argument_fact', [
            'argument_id' => $argument->id,
            'fact_id' => $fact->id,
            'is_supportive' => false,
        ]);
    }

    public function test_you_cannot_join_facts_and_arguments_twice()
    {
        $this->expectException(QueryException::class);

        $argument = factory(Argument::class)->create();
        $fact = factory(Fact::class)->create();
        $argument->facts()->attach($fact);
        $argument->facts()->attach($fact);

        $this->assertTrue(true);
    }

    public function test_arguments_are_attached_as_supportive_by_default()
    {
        $argument = factory(Argument::class)->create();
        $fact = factory(Fact::class)->create();
        $argument->facts()->attach($fact);

        $this->assertDatabaseHas('argument_fact', [
            'argument_id' => $argument->id,
            'fact_id' => $fact->id,
            'is_supportive' => true,
        ]);
    }
}