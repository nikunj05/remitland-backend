<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Bonham',
                'email' => 'john@email.com',
                'password' => 'John@1234',
                'type' => 'individual',
                'country' => 'United States',
                'bank_name' => 'Bank of America',
                'branch_name' => 'Main Street Branch',
                'swift_code' => 'KJA98127',
                'account_number' => '1982631287368',
            ],
            [
                'name' => 'Emily Carter',
                'email' => 'emily@email.com',
                'password' => 'Emily@1234',
                'type' => 'individual',
                'country' => 'United Arab Emirates',
                'bank_name' => 'Emirates NBD',
                'branch_name' => 'Dubai Main Branch',
                'swift_code' => 'EBILAEAD',
                'account_number' => '3874629104857',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@email.com',
                'password' => 'Sarah@1234',
                'type' => 'business',
                'country' => 'Canada',
                'bank_name' => 'Royal Bank of Canada',
                'branch_name' => 'Toronto Branch',
                'swift_code' => 'ROYCCAT2',
                'account_number' => null,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'email_verified_at' => now(),
                    'password' => Hash::make($user['password']),
                ]
            );
        }
    }
}