<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillsDueTomorrowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bills;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct($bills, $locale)
    {
        $this->bills = $bills;
        $this->locale = $locale;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $userEnabledNotificationChannels = $notifiable->enabledNotificationChannels()->pluck('slug')->toArray();

        return $userEnabledNotificationChannels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        App::setLocale($this->locale);

        $message = (new MailMessage())
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->salutation(__('Best regards, Your Guardian.'))
            ->greeting(__('Hello :name', ['name' => $notifiable->first_name]))
            ->subject(__('Bills Due Tomorrow'));

        $billsTitles = $this->bills
            ->map(function ($bill) {
                return '"' . $bill->title . '"';
            })
            ->join(', ');

        $message->line(
            __('Your bills :bills are due tomorrow.', ['bills' => $billsTitles])
        );

        $message
            ->action(__('View Bills'), url('/bills'))
            ->line(
                __(
                    'Please make sure to pay them on time to avoid any late fees.'
                )
            )
            ->line(
                __(
                    'If you have already paid these bills, no further action is required.'
                )
            );

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'message' => __('You\'ve got bills due tomorrow.'),
            'url' => route('bills.index', ['filterByStatuses' => ['overdue'], 'sortByDueDate' => 'desc']),
        ];
    }
}
