<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillsOverdueNotification extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        App::setLocale($this->locale);

        $message = (new MailMessage())
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->salutation(__('Best regards, Your Guardian.'))
            ->greeting(__('Hello :name', ['name' => $notifiable->first_name]))
            ->subject(__('Bills Overdue'));

        $message->line(__('You have one or more bills now overdue.'));

        $message
            ->action(
                __('View Bills'),
                url('/bills?filterByStatus%5B%5D=overdue&sortByDueDate=desc')
            )
            ->line(
                __(
                    'Make sure to pay them as soon as possible to avoid extra fees.'
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
    public function toArray(object $notifiable): array
    {
        return [
                //
            ];
    }
}
