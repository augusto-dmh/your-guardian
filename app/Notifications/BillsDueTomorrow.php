<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillsDueTomorrow extends Notification
{
    use Queueable;

    protected $bills;

    /**
     * Create a new notification instance.
     */
    public function __construct($bills)
    {
        $this->bills = $bills;
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
        $message = (new MailMessage())
            ->from('app.yourguardian@gmail.com', 'YourGuardian')
            ->salutation('Best regards, Your Guardian.')
            ->greeting(Lang::get("Hello $notifiable->first_name"))
            ->subject(Lang::get('Bills Due Tomorrow Notification'));

        $billsTitles = $this->bills
            ->map(function ($bill) {
                return '"' . $bill->title . '"';
            })
            ->join(', ');

        $message->line(
            Lang::get('Your bills ' . $billsTitles . ' are due tomorrow.')
        );

        $message
            ->action(Lang::get('View Bills'), url('/bills'))
            ->line(
                Lang::get(
                    'Please make sure to pay them on time to avoid any late fees.'
                )
            )
            ->line(
                Lang::get(
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
            'bills' => $this->bills
                ->map(function ($bill) {
                    return [
                        'title' => $bill->title,
                        'due_date' => $bill->due_date,
                    ];
                })
                ->toArray(),
        ];
    }
}
