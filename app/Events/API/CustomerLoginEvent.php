<?php

declare(strict_types = 1);

namespace App\Events\API;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CustomerLoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $customer;
    /**
     * @var string
     */
    public $tokenId;
    /**
     * @var Carbon
     */
    public $eventTime;
    /**
     * @var string
     */
    public $type = 'logged_in';

    /**
     * Create a new event instance.
     *
     * @param User $customer
     * @param string $tokenId
     * @param Carbon $eventTime
     */
    public function __construct(User $customer, string $tokenId, Carbon $eventTime)
    {
        $this->customer = $customer;
        $this->tokenId = $tokenId;
        $this->eventTime = $eventTime;
    }
}
