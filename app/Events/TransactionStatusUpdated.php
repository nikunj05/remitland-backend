<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Transaction $transaction,
        public string $oldStatus,
        public string $newStatus,
    ) {
    }

    public function broadcastOn(): array
    {
        return ['transactions'];
    }

    public function broadcastAs(): string
    {
        return 'transactions';
    }

    public function broadcastWith(): array
    {
        return $this->transaction->toArray() + [
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
