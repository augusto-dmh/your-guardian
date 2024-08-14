<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Task;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $migrations = glob(database_path('migrations/*.php'));

        $addTriggerMigrations = array_filter($migrations, function (
            $migration
        ) {
            return strpos($migration, 'add_trigger') !== false;
        });

        $this->dropTriggers(); // ensure that the triggers are dropped (an error occurs when just rolling back)
        $this->rollbackAddTriggerMigrations($addTriggerMigrations);

        $user = User::create([
            'first_name' => 'Augusto',
            'last_name' => 'Henriques',
            'birthdate' => '1990-06-03',
            'email' => 'augustodemelohenriques@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        Bill::factory()
            ->count(5000)
            ->createQuietly([
                'user_id' => $user->id,
            ]);

        Transaction::factory()
            ->count(5000)
            ->createQuietly([
                'user_id' => $user->id,
            ]);

        Task::factory()
            ->count(5000)
            ->createQuietly([
                'user_id' => $user->id,
            ]);

        $this->executeAddTriggerMigrations($addTriggerMigrations);
    }

    /**
     * Drop existing triggers in the database.
     */
    protected function dropTriggers()
    {
        $triggers = [
            'restrict_paid_at_update',
            'before_transactions_insert_adjust_amount',
            'before_transactions_update_adjust_amount',
            'before_transactions_insert_check_type',
            'before_transactions_update_check_type',
            'set_paid_at_before_insert',
            'set_paid_at_before_update',
            'insert_transaction_after_insert_on_bills',
            'insert_transaction_after_update_on_bills',
            'after_transaction_categories_update_in_type',
        ];

        foreach ($triggers as $trigger) {
            try {
                DB::statement("DROP TRIGGER IF EXISTS $trigger");
            } catch (\Exception $e) {
                Log::error("Failed to drop trigger: $trigger", [
                    'exception' => $e,
                ]);
            }
        }
    }

    /**
     * Execute the 'add_trigger' migrations.
     *
     * @param array $addTriggerMigrations
     */
    protected function executeAddTriggerMigrations($addTriggerMigrations)
    {
        foreach ($addTriggerMigrations as $migration) {
            // Convert absolute path to relative path (--path receives only relative ones)
            $relativePath = str_replace(
                base_path() . DIRECTORY_SEPARATOR,
                '',
                $migration
            );

            Artisan::call('migrate', [
                '--path' => $relativePath,
            ]);
        }
    }

    /**
     * Rollback the 'add_trigger' migrations.
     *
     * @param array $addTriggerMigrations
     */
    protected function rollbackAddTriggerMigrations($addTriggerMigrations)
    {
        foreach ($addTriggerMigrations as $migration) {
            // Convert absolute path to relative path (--path receives only relative ones)
            $relativePath = str_replace(
                base_path() . DIRECTORY_SEPARATOR,
                '',
                $migration
            );

            Artisan::call('migrate:rollback', [
                '--path' => $relativePath,
            ]);
        }
    }
}
