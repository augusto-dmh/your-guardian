<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notification_channels')->insert([
            ['name' => 'E-mail', 'slug' => 'mail'],
            ['name' => 'In-app', 'slug' => 'database'],
        ]);
    }
}
