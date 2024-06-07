<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Augusto',
            'last_name' => 'Henriques',
            'birthdate' => '1990-06-03',
            'email' => 'augustodemelohenriques@gmail.com',
            'password' => '123123123',
        ]);

        Bill::factory()
            ->count(10)
            ->create([
                'user_id' => $user->id,
            ]);

        Transaction::factory()
            ->count(10)
            ->create([
                'user_id' => $user->id,
            ]);

        Task::factory()
            ->count(10)
            ->create([
                'user_id' => $user->id,
            ]);
    }
}
