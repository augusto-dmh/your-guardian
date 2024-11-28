<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})
    ->purpose('Display an inspiring quote')
    ->hourly();

Schedule::command('send-notifications:bills-due-tomorrow')
    ->timezone('America/Sao_Paulo')
    ->dailyAt('05:00');

Schedule::command('send-notifications:bills-overdue')
    ->timezone('America/Sao_Paulo')
    ->dailyAt('05:00');
