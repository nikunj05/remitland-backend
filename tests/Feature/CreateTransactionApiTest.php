<?php

use App\Events\TransactionCreated;
use App\Jobs\CreateTransactionJob;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('it queues transaction creation', function () {
    Queue::fake();

    $user = User::factory()->create([
        'account_number' => 'AC-10001',
    ]);

    $payload = [
        'user_id' => $user->id,
        'type' => 'send_money',
        'to_name' => 'Alice Green',
        'amount' => 1250.50,
        'currency' => 'usd',
    ];

    $response = postJson('/api/transactions', $payload);

    $response
        ->assertStatus(202)
        ->assertJsonPath('status', true)
        ->assertJsonPath('message', 'Transaction queued successfully.')
        ->assertJsonPath('data.queued', true);

    Queue::assertPushed(CreateTransactionJob::class, function (CreateTransactionJob $job) use ($user) {
        return $job->payload['user_id'] === $user->id
            && $job->payload['currency'] === 'USD'
            && ! empty($job->payload['request_id']);
    });

    $requestId = $response->json('data.request_id');

    expect(Transaction::where('request_id', $requestId)->exists())->toBeFalse();
});

test('create transaction job persists transaction', function () {
    Event::fake([TransactionCreated::class]);

    $user = User::factory()->create([
        'account_number' => 'AC-90001',
    ]);

    $payload = [
        'request_id' => 'REQTXN01',
        'user_id' => $user->id,
        'type' => 'add_money',
        'to_name' => 'Wallet Topup',
        'amount' => 75.00,
        'currency' => 'aed',
    ];

    (new CreateTransactionJob($payload))->handle();

    Event::assertDispatched(TransactionCreated::class);

    $transaction = Transaction::where('request_id', 'REQTXN01')->first();

    expect($transaction)->not->toBeNull();
    expect($transaction->user_id)->toBe($user->id);
    expect($transaction->account_number)->toBe('AC-90001');
    expect($transaction->type)->toBe('add_money');
    expect($transaction->to_name)->toBe('Wallet Topup');
    expect($transaction->currency)->toBe('AED');
    expect($transaction->status)->toBe('pending');
});
