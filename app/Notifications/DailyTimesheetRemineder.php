<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyTimesheetRemineder extends Notification
{
    use Queueable;

    private $date;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        //
        $this->date = $date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader('X-PM-Message-Stream: outbound');
            })
            ->greeting('Хей,')
            ->subject("❗Reminder: Не си въвел часовтете си за ".$this->date."!")
            ->line('Това е само приятелско напомняне, да въведеш часовете си за вчера ('.$this->date.'), докато ти е все още прясно какво си правил.')
            ->line('**Направи го сега!**')
            ->priority(1)
            ->action('Към airtable.com', url('https://airtable.com/app0BoC8WBjwmGwMP/tblPZU1KDBWoMaRZG/viw5eetY2XrNHkTtf?blocks=hide'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
