<?php

namespace Tests\Unit;

use App\Fact;
use App\Issue;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactIssueModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_join_facts_and_issues()
    {
        $fact = factory(Fact::class)->create();
        $issue = factory(Issue::class)->create();
        $fact->issues()->attach($issue);

        $this->assertDatabaseHas('fact_issue', [
            'fact_id' => $fact->id,
            'issue_id' => $issue->id,
        ]);
    }

    public function test_you_cannot_join_facts_and_issues_twice()
    {
        $this->expectException(QueryException::class);

        $fact = factory(Fact::class)->create();
        $issue = factory(Issue::class)->create();
        $fact->issues()->attach($issue);
        $fact->issues()->attach($issue);

        $this->assertTrue(true);
    }
}