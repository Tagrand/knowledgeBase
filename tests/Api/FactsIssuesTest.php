<?php

namespace Tests\Api;

use App\User;
use App\Fact;
use App\Issue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class FactsIssuesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_an_issue_fact_join()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/facts/{$fact->id}/issues/{$issue->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('fact_issue', [
            'fact_id' => $fact->id,
            'issue_id' => $issue->id,
        ]);
    }
}
