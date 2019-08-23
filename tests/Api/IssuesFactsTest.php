<?php

namespace Tests\Api;

use App\Fact;
use App\User;
use App\Issue;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IssuesFactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_all_related_facts() {
        $user = factory(User::class)->create();
        $fact1 = factory(Fact::class)->create();
        $fact2 = factory(Fact::class)->create();
        $unrelatedFact = factory(Fact::class)->create();
        $issue = factory(Issue::class)->create();
        $issue->facts()->attach([$fact1->id, $fact2->id]);

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/issues/{$issue->id}/facts");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }
}