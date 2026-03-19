<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique(); // e.g. 6A5S1D5A
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('account_number')->nullable();
            $table->enum('type', ['send_money', 'add_money'])->default('send_money');
            $table->string('to_name');             // e.g. "John Bonham", "Emily Carter"
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);        // USD, AED, CAD
            $table->enum('status', [
                'pending',
                'approved',
                'cancelled',
                'rejected',
                'success'
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
