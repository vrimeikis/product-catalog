<?php

declare(strict_types = 1);

namespace Modules\ContactUs\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\ContactUs\Emails\NewMessageMail;
use Modules\ContactUs\Entities\ContactMessage;

/**
 * Class NewMessageJob
 * @package Modules\ContactUs\Jobs
 */
class NewMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ContactMessage
     */
    protected $message;

    public $tries = 3;

    public $retryAfter = 5;

    /**
     * Create a new job instance.
     *
     * @param ContactMessage $message
     */
    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('admin@admin.com')
            ->send(new NewMessageMail($this->message));
    }

    public function failed(Exception $exception)
    {

    }
}
