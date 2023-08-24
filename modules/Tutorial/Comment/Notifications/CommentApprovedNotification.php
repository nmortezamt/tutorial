<?php

namespace Tutorial\Comment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use Tutorial\Comment\Mail\CommentApprovedMail;
use Tutorial\Comment\Models\Comment;

class CommentApprovedNotification extends Notification
{
    use Queueable;

    public $comment;
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        $channels = [
            'mail',
            'database'
        ];
        if(! empty($notifiable->telegram)) $channels[] = 'telegram';
        return $channels;
    }

    public function toMail(object $notifiable): Mailable
    {
        return (new CommentApprovedMail($this->comment))->to($notifiable->email);
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
        ->to($notifiable->telegram)
        ->content("سلام دانشجو \n دیدگاه شما تایید شد. در سایت".env('APP_NAME'))
        ->button('مشاهده دوره', $this->comment->commentable->path());
    }

    public function toArray(object $notifiable): array
    {
        return [
            "message"=> "دیدگاه شما تایید شد",
            "url" => $this->comment->commentable->path(),
        ];
    }
}
