<?php

namespace App\Mail;

use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemberNotificationMail extends Mailable implements MailableContract
{
    use Queueable, SerializesModels;

    public string $notifTitle;
    public string $notifMessage;
    public string $memberName;

    public function __construct(string $title, string $message, string $memberName)
    {
        $this->notifTitle   = $title;
        $this->notifMessage = $message;
        $this->memberName   = $memberName;
    }

    public function build(): static
    {
        return $this->subject($this->notifTitle)
                    ->view('emails.member-notification');
    }
}
