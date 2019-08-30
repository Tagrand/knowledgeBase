<?php

namespace Tests\Unit;

use App\Issue;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IssueModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_make_an_issue()
    {
        $issue = new Issue([
            'name' => 'Global warming',
            'summary' => 'Global',
        ]);

        $this->assertTrue($issue->save());
        $this->assertDatabaseHas('issues', [
            'id' => $issue->id,
            'name' => 'Global warming',
            'summary' => 'Global',
        ]);
    }

    public function test_summary_is_nullable()
    {
        $issue = new Issue([
            'name' => 'Global warming',
        ]);

        $this->assertTrue($issue->save());
        $this->assertDatabaseHas('issues', [
            'id' => $issue->id,
            'name' => 'Global warming',
        ]);
    }

    public function test_name_is_required()
    {
        $this->expectException(QueryException::class);
        $issue = new Issue([
            'summary' => 'Global warming',
        ]);

        $this->assertFalse($issue->save());
    }

    public function test_name_is_unique()
    {
        $this->expectException(QueryException::class);
        Issue::create([
            'name' => 'Poverty',
        ]);
        $issue = new Issue([
            'name' => 'Poverty',
        ]);

        $this->assertFalse($issue->save());
    }
}