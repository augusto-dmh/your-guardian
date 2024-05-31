<?php

namespace Database\Seeders;

use App\Models\TaskCategory;
use App\Models\TransactionCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        TransactionCategory::factory(10)->create();
        TaskCategory::factory(10)->create();
    }
}
