<?php

namespace Tests\Unit;

use App\Argument;
use App\Source;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_make_an_argument()
    {
        $argument = new Argument([
            'reason' => 'Science',
        ]);

        $this->assertTrue($argument->save());
        $this->assertDatabaseHas('arguments', [
            'reason' => 'Science',
        ]);
    }

    public function test_arguments_can_be_linked_to_a_source()
    {
        $source = factory(Source::class)->create();
        $argument = new Argument([
            'reason' => 'Faith',
            'source_id' => $source->id,
        ]);

        $this->assertTrue($argument->save());
        $this->assertDatabaseHas('arguments', [
            'reason' => 'Faith',
            'source_id' => $source->id,
        ]);
    }

    public function test_reasons_are_required()
    {
        $this->expectException(QueryException::class);
        $argument = new Argument();

        $this->assertFalse($argument->save());
    }

    public function test_reasons_must_be_unique()
    {
        $this->expectException(QueryException::class);
        Argument::create([
            'reason' => 'I saw it in a dream',
        ]);
        $argument = new Argument([
            'reason' => 'I saw it in a dream',
        ]);

        $this->assertFalse($argument->save());
    }
}