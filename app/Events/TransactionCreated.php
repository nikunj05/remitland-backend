<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
    }

    public function broadcastOn(): array
    {
        return ['transactions'];
    }

    public function broadcastAs(): string
    {
        return 'transactions.created';
    }

    public function broadcastWith(): array
    {
        return $this->transaction->toArray();
    }
}
