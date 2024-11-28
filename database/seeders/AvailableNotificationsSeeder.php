<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AvailableNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('available_notifications')->insert(['name' => 'Bills Overdue', 'description' => 'Receive daily when at least one bill is overdue.']);
        DB::table('available_notifications')->insert(['name' => 'Bills Due Tomorrow', 'description' => 'Receive one day before the due date of a bill comes.']);
    }
}
