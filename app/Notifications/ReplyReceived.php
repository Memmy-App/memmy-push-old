<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;

class ReplyReceived extends Notification
{
    use Queueable;

    private int $replyId;
    private string $sender;
    private string $content;

    public function __construct($reply)
    {
        $this->replyId = $reply["id"];
        $this->sender = $reply["user"];
        $this->content = $reply["content"];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [ApnChannel::class];
    }

    public function toApn(object $notifiable) {
        try {
            return ApnMessage::create()
                ->title("$this->sender replied to you")
                ->body($this->content)
                ->sound("default");
        } catch(\Exception $e) {
            error_log($e);
        }
    }
}
