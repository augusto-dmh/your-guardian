<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\TestUserSeeder;
use Database\Seeders\TaskCategorySeeder;
use Database\Seeders\TransactionCategorySeeder;
use Database\Seeders\AvailableNotificationsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TaskCategorySeeder::class,
            TransactionCategorySeeder::class,
            AvailableNotificationsSeeder::class,
            TestUserSeeder::class,
        ]);
    }
}
