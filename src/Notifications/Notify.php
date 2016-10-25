<?php

namespace BrandonFerens\Watchdog\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class Notify extends Notification
{
    /**
     * @var string
     */
    private $queueName;

    /**
     * Create a new notification instance.
     *
     * @param string $queue
     */
    public function __construct(string $queue)
    {
        $this->queueName = $queue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return config('watchdog.channels');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        $domain = url('/');

        return (new MailMessage)
            ->subject("[Watchdog] {$this->queueName} is down")
            ->greeting('Watchdog sniffed a problem!')
            ->line("The queue '{$this->queueName}' is down on {$domain}");
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @return SlackMessage
     */
    public function toSlack()
    {
        return (new SlackMessage)
            ->error()
            ->content('Watchdog sniffed a problem!')
            ->attachment(function ($attachment) {
                $attachment->fields(
                    [
                        'Queue'  => $this->queueName,
                        'Status' => 'Down',
                        'Domain' => url('/'),
                    ]
                );
            });
    }
}
