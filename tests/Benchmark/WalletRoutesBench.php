<?php

namespace App\tests\Benchmark;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class WalletRoutesBench
{
    public function __construct()
    {
        $this->bootstrapLaravel();
    }

    private function bootstrapLaravel()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchShow()
    {
        DB::beginTransaction(); /* wrapping up even here to avoid cache table to persist data */

        $user = User::first(); /* assuming that the TestUserSeeder has already been run. */
        Auth::login($user);

        Route::dispatch(Request::create('/wallet', 'GET'));

        DB::rollback();
    }
}
