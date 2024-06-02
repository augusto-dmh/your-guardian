<?php

Route::get('/buttons/text', function () {
    return view('buttons-showcase.text');
})
    ->middleware(['auth'])
    ->name('buttons.text');

Route::get('/buttons/icon', function () {
    return view('buttons-showcase.icon');
})
    ->middleware(['auth'])
    ->name('buttons.icon');

Route::get('/buttons/text-icon', function () {
    return view('buttons-showcase.text-icon');
})
    ->middleware(['auth'])
    ->name('buttons.text-icon');
