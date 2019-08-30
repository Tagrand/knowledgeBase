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

    public function test_you_cannot_join_sources_and_authors_twice()
    {
        $this->expectException(QueryException::class);

        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();
        $source->authors()->attach($author);
        $source->authors()->attach($author);

        $this->assertTrue(true);
    }
}