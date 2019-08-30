<?php

namespace Tests\Unit;

use App\Fact;
use App\Source;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_make_a_fact()
    {
        $fact = new Fact([
            'claim' => 'Climate change is real',
        ]);

        $this->assertTrue($fact->save());
        $this->assertDatabaseHas('facts', [
            'claim' => 'Climate change is real',
        ]);
    }

    public function test_facts_can_be_linked_to_a_source()
    {
        $source = factory(Source::class)->create();
        $fact = new Fact([
            'claim' => 'Climate change is real',
            'source_id' => $source->id,
        ]);

        $this->assertTrue($fact->save());
        $this->assertDatabaseHas('facts', [
            'claim' => 'Climate change is real',
            'source_id' => $source->id,
        ]);
    }

    public function test_claims_are_required()
    {
        $this->expectException(QueryException::class);
        $fact = new Fact();

        $this->assertFalse($fact->save());
    }

    public function test_claims_must_be_unique()
    {
        $this->expectException(QueryException::class);
        factory(Fact::class)->create([
            'claim' => 'The truth',
        ]);
        $fact = new Fact([
            'claim' => 'The truth',
        ]);

        $this->assertFalse($fact->save());
    }
}