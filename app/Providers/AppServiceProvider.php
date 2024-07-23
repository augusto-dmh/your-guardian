<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(60);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage())
                ->from('app.yourguardian@gmail.com', 'YourGuardian')
                ->salutation('Best regards, Your Guardian.')
                ->greeting(Lang::get("Hello $notifiable->first_name"))
                ->subject(Lang::get('Verify Email Address'))
                ->line(
                    Lang::get(
                        'Please click the button below to verify your email address.'
                    )
                )
                ->action(Lang::get('Verify Email Address'), $url)
                ->line(
                    Lang::get(
                        'If you did not create an account, no further action is required.'
                    )
                );
        });
    }
}
