<?php

declare(strict_types = 1);

namespace Modules\ContactUs\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ContactUs\Entities\ContactMessage;

class NewMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var ContactMessage
     */
    public $contactMessage;

    /**
     * Create a new message instance.
     *
     * @param ContactMessage $contactMessage
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('contactus::emails.new_message');
    }
}
