<?php

namespace Tests\Unit;

use App\Issue;
use App\Argument;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentIssueTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_join_arguments_and_issues()
    {
        $argument = factory(Argument::class)->create();
        $issue = factory(Issue::class)->create();
        $argument->issues()->attach($issue);

        $this->assertDatabaseHas('argument_issue', [
            'argument_id' => $argument->id,
            'issue_id' => $issue->id,
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