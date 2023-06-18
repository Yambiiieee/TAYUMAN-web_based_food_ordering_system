<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReceipt extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $email;
    public $total;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $email, $total, $data)
    {
        $this->first_name = $first_name;
        $this->email = $email;
        $this->total = $total;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('send_receipt');
    }
}