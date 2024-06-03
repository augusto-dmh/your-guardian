<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\TestUserSeeder;
use Database\Seeders\TaskCategorySeeder;
use Database\Seeders\TransactionCategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TestUserSeeder::class,
            TaskCategorySeeder::class,
            TransactionCategorySeeder::class,
        ]);
    }
}
