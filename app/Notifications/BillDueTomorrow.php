<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillDueTomorrow extends Notification
{
    use Queueable;

    protected $bill;

    /**
     * Create a new notification instance.
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
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
        return (new MailMessage())
            ->from('app.yourguardian@gmail.com', 'YourGuardian')
            ->salutation('Best regards, Your Guardian.')
            ->greeting(Lang::get("Hello $notifiable->first_name"))
            ->subject(Lang::get('Bill Due Tomorrow Notification'))
            ->line(
                Lang::get(
                    'Your bill "' . $this->bill->title . '" is due tomorrow.'
                )
            )
            ->action(Lang::get('View Bill'), url('/bills/' . $this->bill->id))
            ->line(
                Lang::get(
                    'Please make sure to pay it on time to avoid any late fees.'
                )
            )
            ->line(
                Lang::get(
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
