<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

test('it returns user list', function () {
    User::factory()->count(3)->create();

    $response = getJson('/api/users');

    $response
        ->assertOk()
        ->assertJsonPath('status', true)
        ->assertJsonPath('message', 'Users retrieved successfully.')
        ->assertJsonCount(3, 'data');

    $responseData = $response->json('data');

    expect($responseData[0])->not->toHaveKey('password');
    expect($responseData[0])->not->toHaveKey('remember_token');
});