<?php

namespace Tests\Api;

use App\User;
use App\Issue;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IssuesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_get_all_issues() {
      $user = factory(User::class)->create();
      factory(Issue::class, 2)->create();

      Passport::actingAs($user);
      $response = $this->json('GET', '/api/v1/issues');

      $response->assertStatus(200);
      $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_get_all_issues() {
      factory(Issue::class, 2)->create(); 

      $response = $this->json('GET', '/api/v1/issues');

      $response->assertStatus(401);
    }

    public function test_a_user_can_make_an_issue() {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);
    }

    public function test_a_guest_cannot_make_an_issue() {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(401);
    }

    public function test_a_issue_must_have_a_name() {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            // 'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }
}