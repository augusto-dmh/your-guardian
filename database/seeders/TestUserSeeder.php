<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Task;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement('SET @DISABLE_TRIGGERS = TRUE;');

        $user = User::create([
            'first_name' => 'Augusto',
            'last_name' => 'Henriques',
            'birthdate' => '1990-06-03',
            'email' => 'augustodemelohenriques@gmail.com',
            'password' => '123123123',
        ]);

        Bill::factory()
            ->count(5000)
            ->create([
                'user_id' => $user->id,
            ]);

        // bills trigger to create transaction based on paid bill
        $bills = $user->bills;
        foreach ($bills as $bill) {
            if ($bill->status === 'paid') {
                if (mt_rand(0, 1)) {
                    $bill->paid_at = fake()->date($bill->due_date);
                    $bill->save();
                }

                $bill->transactions()->create([
                    'user_id' => $bill->user_id,
                    'bill_id' => $bill->id,
                    'amount' => $bill->amount,
                    'type' => 'expense',
                    'title' => $bill->title,
                    'description' => $bill->description,
                ]);
            }
        }

        Transaction::factory()
            ->count(5000)
            ->create([
                'user_id' => $user->id,
            ]);

        // transactions trigger to ensure amount is consistent with type
        $transactions = $user->transactions;
        foreach ($transactions as $transaction) {
            $transaction->amount =
                $transaction->type === 'income'
                    ? abs($transaction->amount)
                    : -abs($transaction->amount);
        }
        $transactions->each->save();

        Task::factory()
            ->count(5000)
            ->create([
                'user_id' => $user->id,
            ]);

        DB::statement('SET @DISABLE_TRIGGERS = FALSE;');
    }
}
