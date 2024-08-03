<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillDueTomorrow extends Notification
{
    use Queueable;

    protected $bill;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct($bill, $locale)
    {
        $this->bill = $bill;
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
    public function toMail($notifiable)
    {
        App::setLocale($this->locale);

        return (new MailMessage())
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->salutation(__('Best regards, Your Guardian.'))
            ->greeting(__('Hello :name', ['name' => $notifiable->first_name]))
            ->subject(__('Bill Due Tomorrow'))
            ->line(
                __('Your bill ":title" is due tomorrow.', [
                    'title' => $this->bill->title,
                ])
            )
            ->action(__('View Bill'), url('/bills/' . $this->bill->id))
            ->line(
                __('Please make sure to pay it on time to avoid any late fees.')
            )
            ->line(
                __(
                    'If you have already paid this bill, no further action is required.'
                )
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->bill->title,
            'due_date' => $this->bill->due_date,
        ];
    }
}
