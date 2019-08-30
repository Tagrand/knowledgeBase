<?php

namespace Tests\Unit;

use App\Author;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_make_a_author()
    {
        $author = new Author([
            'first_name' => 'Rashida',
            'last_name' => 'Tlaib',
        ]);

        $this->assertTrue($author->save());
        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'first_name' => 'Rashida',
            'last_name' => 'Tlaib',
        ]);
    }

    public function test_first_name_cannot_be_null()
    {
        $this->expectException(QueryException::class);
        $author = new author([
            'first_name' => 'Ayanna',
        ]);

        $this->assertFalse($author->save());
    }

    public function test_last_name_cannot_be_null()
    {
        $this->expectException(QueryException::class);
        $author = new author([
            'last_name' => 'Pressley',
        ]);

        $this->assertFalse($author->save());
    }

    public function test_first_and_last_name_combination_is_unique()
    {
        $this->expectException(QueryException::class);
        Author::create([
            'first_name' => 'Ilhan',
            'last_name' => 'Omar',
        ]);
        $author = new Author([
            'first_name' => 'Ilhan',
            'last_name' => 'Omar',
        ]);

        $this->assertFalse($author->save());
    }
}
