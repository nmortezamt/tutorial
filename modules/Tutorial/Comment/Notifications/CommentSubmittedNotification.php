<?php

namespace Tutorial\Comment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use Tutorial\Comment\Mail\CommentSubmittedMail;
use Kavenegar\LaravelNotification\KavenegarChannel;
use Tutorial\Comment\Models\Comment;

class CommentSubmittedNotification extends Notification
{
    use Queueable;

    public $comment;
    public $isTeacher;
    public function __construct(Comment $comment, $isTeacher = false)
    {
        $this->comment = $comment;
        $this->isTeacher = $isTeacher;
    }


    public function via(object $notifiable): array
    {
        $channels = [
            'mail',
            'database'
        ];
        if (!empty($notifiable->telegram)) $channels[] = 'telegram';
        if (!empty($notifiable->mobile)) $channels[] = KavenegarChannel::class;
        return $channels;
    }


    public function toMail(object $notifiable): Mailable
    {
        return (new CommentSubmittedMail($this->comment,$this->isTeacher))->to($notifiable->email);
    }

    public function toTelegram($notifiable)
    {
        if ($this->isTeacher == true) {
            return TelegramMessage::create()
                ->to($notifiable->telegram)
                ->content("سلام مدرس \n یک دیدگاه جدید برای دوره شما در سایت" . env('APP_NAME') . "ارسال شده است.")
                ->button('مشاهده دوره', $this->comment->commentable->path())
                ->button('مدیریت دیدگاه ها', route('comments.index'));
        } elseif ($this->isTeacher == false) {
            return TelegramMessage::create()
                ->to($notifiable->telegram)
                ->content("سلام دانشجو \n به دیدگاه شما یک پاسخ در سایت" . env('APP_NAME') . "ارسال شده است.")
                ->button('مشاهده دوره', $this->comment->commentable->path());
        }
    }

    public function toSMS($notifiable)
    {
        if ($this->isTeacher == true) {
            return ' یک دیدگاه جدید برای دوره ی شما در سایت ' . env('APP_NAME') . " ارسال شده است. جهت مشاهده و ارسال پاسخ روی لینک زیر کلیک فرمایید. \n" . route('comments.index');
        } elseif ($this->isTeacher == false) {
            return "سلام دانشجو عزیز \n به دیدگاه شما یک پاسخ در سایت " . env('APP_NAME') . " ارسال شده است. جهت مشاهده به لینک زیر مراجعه کنید \n." . $this->comment->commentable->path();
        }
    }

    public function toArray(object $notifiable): array
    {
        return [
            "message" => $this->isTeacher ? "یک دیدگاه جدید برای دوره شما ثبت شد" : "به دیدگاه شما یک پاسخ ارسال شد",
            "url" =>$this->isTeacher ? route('comments.index') : $this->comment->commentable->path(),
        ];
    }
}
