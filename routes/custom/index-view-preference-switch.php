<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/index-view-preference-switch/{preference}', function (
        $preference
    ) {
        if (in_array($preference, ['cards', 'table'])) {
            Auth::user()->index_view_preference = $preference;
            Auth::user()->save();
        }

        return redirect()->back();
    })->name('index-view-preference');
});
