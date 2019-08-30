<?php

namespace Tests\Unit;

use App\Source;
use App\Author;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourceAuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_join_sources_and_authors()
    {
        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();
        $source->authors()->attach($author);

        $this->assertDatabaseHas('author_source', [
            'source_id' => $source->id,
            'author_id' => $author->id,
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
}