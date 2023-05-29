<?php

namespace App\Listeners;

use App\Mail\MailNotify;
// use App\Events\SendVerificationCode;
use App\Events\SendVerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerificationCodeMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationCodeListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  SendVerificationCodeMail  $event
     * @return void
     */
    public function handle(SendVerificationCode $event)
    {
        Mail::to($event->email)->send(new MailNotify([
            'verificationCode' => $event->verificationCode,
            'email' => $event->email
        ]));
    }
}
