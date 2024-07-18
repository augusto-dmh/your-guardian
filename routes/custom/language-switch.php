<?php

use Illuminate\Support\Facades\App;

Route::get('/language-switch/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'pt_BR'])) {
        Auth::user()->language_preference = $locale;
        Auth::user()->save();
        App::setLocale($locale);
    }

    return redirect()->back();
})->name('locale');
