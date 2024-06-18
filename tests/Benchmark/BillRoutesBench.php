<?php

namespace App\tests\Benchmark;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Console\Kernel;
use App\Http\Requests\Bill\BillStoreRequest;
use App\Http\Requests\Bill\BillUpdateRequest;

/* run TestUserSeeder (if you haven't already) before benchmarking these routes. */

class BillRoutesBench
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
    public function benchStore()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        $request = new BillStoreRequest();
        $data = Bill::factory()->make()->toArray();
        $request->merge($data);

        Route::dispatch(Request::create('/bills', 'POST', $request->all()));

        DB::rollback();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchIndex()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        $request = new Request();
        // $data = ['sortByDueDate' => 'desc']; /* uncomment these and adjust if you want to benchmark this route with filtering applied */
        // $request->merge($data);

        Route::dispatch(Request::create('/bills', 'GET', $request->all()));

        DB::rollback();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchShow()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        $bill = Bill::first();

        Route::dispatch(Request::create("/bills/{$bill->id}", 'GET'));

        DB::rollback();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchUpdate()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        $bill = Bill::first();

        $request = new BillUpdateRequest();
        $data = Bill::factory()->make()->toArray();
        $request->merge($data);

        Route::dispatch(
            Request::create("/bills/{$bill->id}", 'PUT', $request->all())
        );

        DB::rollback();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchDestroy()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        $bill = Bill::first();

        Route::dispatch(Request::create("/bills/{$bill->id}", 'DELETE'));

        DB::rollback();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchCreate()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        Route::dispatch(Request::create('/bills/create', 'GET'));

        DB::rollback();
    }

    /**
     * @Revs(1000)
     * @Iterations(3)
     */
    public function benchEdit()
    {
        DB::beginTransaction();

        $user = User::first();
        Auth::login($user);

        $bill = Bill::first();

        Route::dispatch(Request::create("/bills/{$bill->id}/edit", 'GET'));

        DB::rollback();
    }
}
