<?php

namespace App\Jobs;

use App\Events\TransactionCreated;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateTransactionJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(public array $payload)
    {
    }

    public function handle(): void
    {
        $user = User::find($this->payload['user_id']);

        if (! $user) {
            return;
        }

        $transaction = Transaction::create([
            'request_id' => (string) $this->payload['request_id'],
            'user_id' => (int) $this->payload['user_id'],
            'account_number' => $this->payload['account_number'] ?? $user->account_number,
            'type' => (string) $this->payload['type'],
            'to_name' => (string) $this->payload['to_name'],
            'amount' => (float) $this->payload['amount'],
            'currency' => strtoupper((string) $this->payload['currency']),
            'status' => $this->payload['status'] ?? 'pending',
        ]);

        event(new TransactionCreated($transaction));
    }
}
