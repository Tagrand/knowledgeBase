<?php

namespace Tests\Api;

use App\User;
use App\Fact;
use App\Issue;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactsIssuesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_related_issues()
    {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();
        $unlinkedIssue = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();
        $fact->issues()->attach([$issue->id, $issue2->id]);
      
        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/facts/{$fact->id}/issues");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $this->assertEquals($issue->id, $response->json()[0]['id']);
    }

    public function test_user_can_see_all_related_issues_even_when_it_has_none()
    {
        $user = factory(User::class)->create();
        $unlinkedIssue = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();
      
        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/facts/{$fact->id}/issues");

        $response->assertStatus(200);
        $this->assertCount(0, $response->json());
    }

    public function test_guest_cannot_see_all_related_issues()
    {
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();
        $fact->issues()->attach([$issue->id, $issue2->id]);
      
        $response = $this->json('GET', "/api/v1/facts/{$fact->id}/issues");

        $response->assertStatus(401);
    }

    public function test_the_fact_must_exist_to_see_issues()
    {
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();
      
        $response = $this->json('GET', "/api/v1/facts/1234/issues");

        $response->assertStatus(401);
    }

    public function test_user_can_create_an_issue_fact_join()
    {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/facts/{$fact->id}/issues/{$issue->id}");

        $response->assertStatus(204);
        $this->assertDatabaseHas('fact_issue', [
            'fact_id' => $fact->id,
            'issue_id' => $issue->id,
        ]);
    }

    public function test_a_guest_cannot_create_an_issue_fact_join()
    {
        $issue = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();

        $response = $this->json('POST', "/api/v1/facts/{$fact->id}/issues/{$issue->id}");

        $response->assertStatus(401);
    }

    public function test_the_issue_must_exist()
    {
        $user = factory(User::class)->create();
        $fact = factory(Fact::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/facts/{$fact->id}/issues/123");

        $response->assertStatus(404);
    }

    public function test_the_fact_must_exist()
    {
        $user = factory(User::class)->create();
        $issue = factory(Fact::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/facts/123/issues/{$issue->id}");

        $response->assertStatus(404);
    }

    public function test_facts_and_issues_can_be_unlinked()
    {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();
        $fact = factory(Fact::class)->create();
        $fact->issues()->attach([$issue->id, $issue2->id]);
      
        Passport::actingAs($user);
        $response = $this->json('DELETE', "/api/v1/facts/{$fact->id}/issues/{$issue->id}");

        $response->assertStatus(204);
        $this->assertCount(1, $fact->fresh()->issues);
        $this->assertDatabaseMissing('fact_issue', [
          'fact_id' => $fact->id,
          'issue_id' => $issue->id
        ]);
    }
}
