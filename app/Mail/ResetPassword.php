<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $token;
    public $email;
    // public $link;

    public function __construct($token, $email)
    {
        //
        $this->token = $token;
        $this->email = $email;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@moovelogic.com')
        ->to($this->email)
        ->subject('RESET YOUR PASSWORD')
        ->view('emails.users.reset')
        ->with(['year' => Carbon::now()->year,])
        ;
    }
}
