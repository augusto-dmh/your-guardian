<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// useless routes
// Just to demo sidebar dropdown links active states.

require __DIR__ . '/auth.php';
require __DIR__ . '/custom/home.php';
require __DIR__ . '/custom/dashboard.php';
require __DIR__ . '/custom/profile.php';
require __DIR__ . '/custom/transaction.php';
require __DIR__ . '/custom/transactionCategory.php';
require __DIR__ . '/custom/bill.php';
require __DIR__ . '/custom/task.php';
require __DIR__ . '/custom/custom-buttons.php';
