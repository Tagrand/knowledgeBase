<?php

namespace Tests\Unit;

use App\Source;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_make_a_source()
    {
        $source = new Source([
            'name' => 'Gareth',
            'summary' => 'kjshdfkjddf',
        ]);

        $this->assertTrue($source->save());
        $this->assertDatabaseHas('sources', [
            'name' => 'Gareth',
            'summary' => 'kjshdfkjddf',
        ]);
    }

    public function test_summary_can_be_null()
    {
        $source = new Source([
            'name' => 'Gareth',
        ]);

        $this->assertTrue($source->save());
        $this->assertDatabaseHas('sources', [
            'name' => 'Gareth',
            'summary' => null,
        ]);
    }

    public function test_name_is_not_optional()
    {
        $this->expectException(QueryException::class);
        $source = new Source([
            'summary' => 'kjshdfkjddf',
        ]);

        $this->assertFalse($source->save());
    }

    public function test_name_must_be_unique()
    {
        $this->expectException(QueryException::class);
        factory(Source::class)->create(['name' => 'tester']);

        $source = new Source([
            'summary' => 'tester',
        ]);

        $this->assertFalse($source->save());
    }
}
