<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('wallet.show', [
            'user' => $user,
        ]);
    }
}
