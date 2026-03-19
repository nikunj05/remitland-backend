<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $john = User::where('email', 'john@email.com')->firstOrFail();

        $transactions = [

            // --- USD Transactions (John) ---
            [
                'request_id'  => '6A5S1D5A',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'John Bonham',
                'amount'      => 12000.00,
                'currency'    => 'USD',
                'status'      => 'pending',
            ],
            [
                'request_id'  => '6A5S1D5B',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'John Bonham',
                'amount'      => 5000.00,
                'currency'    => 'USD',
                'status'      => 'approved',
            ],
            [
                'request_id'  => '6A5S1D5C',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'John Bonham',
                'amount'      => 8500.00,
                'currency'    => 'USD',
                'status'      => 'approved',
            ],

            // --- AED Transactions (John) ---
            [
                'request_id'  => '6A5S1D6A',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'Emily Carter',
                'amount'      => 50000.00,
                'currency'    => 'AED',
                'status'      => 'pending',
            ],
            [
                'request_id'  => '6A5S1D6B',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'Emily Carter',
                'amount'      => 50000.00,
                'currency'    => 'AED',
                'status'      => 'approved',
            ],
            [
                'request_id'  => '6A5S1D6C',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'Emily Carter',
                'amount'      => 25000.00,
                'currency'    => 'AED',
                'status'      => 'approved',
            ],

            // --- CAD Transactions (John) ---
            [
                'request_id'  => '6A5S1D7A',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'John Bonham',
                'amount'      => 15000.00,
                'currency'    => 'CAD',
                'status'      => 'pending',
            ],
            [
                'request_id'  => '6A5S1D7B',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'John Bonham',
                'amount'      => 9000.00,
                'currency'    => 'CAD',
                'status'      => 'approved',
            ],

            // --- Additional transaction ---
            [
                'request_id'  => '6A5S1D8A',
                'user_id'     => $john->id,
                'account_number' => $john->account_number,
                'type'        => 'send_money',
                'to_name'     => 'John Bonham',
                'amount'      => 3000.00,
                'currency'    => 'USD',
                'status'      => 'pending',
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
