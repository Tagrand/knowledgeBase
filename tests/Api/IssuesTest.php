<?php

namespace Tests\Api;

use App\Issue;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class IssuesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_get_all_issues() {
      $user = factory(User::class)->create();
      factory(Issue::class, 2)->create();

      Passport::actingAs($user);
      $response = $this->json('get', '/api/v1/issues');

      $response->assertStatus(200);
      $this->assertCount(2, $response->json());
    }
}