<?php

namespace App\Http\Controllers;

use App\Events\TransactionStatusUpdated;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionStatusRequest;
use App\Http\Responses\ApiResponse;
use App\Jobs\CreateTransactionJob;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $currency = $request->currency ? strtoupper($request->currency) : 'all';
        $transactions = Cache::remember('transactions_' . $currency, 60, function () use ($request, $currency) {
            return Transaction::with('user')
                    ->when($currency !== 'all', fn ($q) => $q->where('currency', $currency))
                    ->latest()
                    ->get()
                    ->toArray();
        });

        return ApiResponse::success($transactions, 'Transactions retrieved successfully.');
    }

    public function show(int $id): JsonResponse
    {
        $transaction = Transaction::with('user')->find($id);

        if (! $transaction) {
            return ApiResponse::error('Transaction not found.', null, 404);
        }

        return ApiResponse::success($transaction, 'Transaction retrieved successfully.');
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $requestId = $this->generateRequestId();

        CreateTransactionJob::dispatch([
            ...$payload,
            'request_id' => $requestId,
        ]);

        Cache::forget('transactions_' . strtoupper($request->currency));
        Cache::forget('transactions_all');

        return ApiResponse::success([
            'queued' => true,
            'request_id' => $requestId,
        ], 'Transaction queued successfully.', 202);
    }

    public function updateStatus(UpdateTransactionStatusRequest $request, int $id): JsonResponse
    {
        $transaction = Transaction::find($id);

        if (! $transaction) {
            return ApiResponse::error('Transaction not found.', null, 404);
        }

        $oldStatus = $transaction->status;
        $transaction->status = $request->status;
        $transaction->save();

        Cache::forget('transactions_' . strtoupper($transaction->currency));
        Cache::forget('transactions_all');

        event(new TransactionStatusUpdated($transaction, $oldStatus, $transaction->status));

        return ApiResponse::success($transaction, 'Transaction status updated successfully.');
    }

    private function generateRequestId(): string
    {
        do {
            $requestId = strtoupper(Str::random(8));
        } while (Transaction::where('request_id', $requestId)->exists());

        return $requestId;
    }
}
