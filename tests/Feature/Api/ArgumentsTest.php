<?php

namespace Tests\Feature\Api;

use App\User;
use App\Source;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentsTest extends TestCase
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


    public function test_a_user_can_edit_an_argument()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'reason' => 'This is a good one',
            'summary' => 'valid point',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('arguments', [
            'id' => $argument->id,
            'summary' => 'valid point',
            'reason' => 'This is a good one',
        ]);
    }

    public function test_users_can_edit_a_facts_source()
    {
        $user= factory(User::class)->create();
        $argument = factory(Argument::class)->create([
            'reason' => 'i like to know the truth',
        ]);
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'source_id' => $source->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('arguments', [
            'id' => $argument->id,
            'reason' => 'i like to know the truth',
            'source_id' => $source->id,
        ]);
    }

    public function test_reasons_cannot_be_null()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'reason' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_edited_reasons_must_be_a_string()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'reason' => 12121,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_edited_reasons_must_be_unique()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        factory(Argument::class)->create([
            'reason' =>'obvious',
        ]);
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'reason' => 'obvious',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_summaries_can_be_null()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'summary' => '',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('arguments', [
             'id' => $argument->id,
            'summary' => null,
        ]);
    }

    public function test_edited_summaries_must_be_strings()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'summary' => 12121,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('summary');
    }

    public function test_source_must_exist_to_be_added()
    {
        $user= factory(User::class)->create();
        $argument = factory(Argument::class)->create([
            'reason' => 'this is false',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/arguments/{$argument->id}", [
            'source_id' => 12612,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('source_id');
    }
}
