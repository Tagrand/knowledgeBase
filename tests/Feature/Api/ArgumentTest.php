<?php

namespace Tests\Feature\Api;

use App\User;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_see_all_arguments()
    {
        $user = factory(User::class)->create();
        factory(Argument::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('GET', '/api/v1/arguments');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_see_arguments()
    {
        factory(Argument::class, 2)->create();

        $response = $this->json('GET', '/api/v1/arguments');

        $response->assertStatus(401);
    }
}
