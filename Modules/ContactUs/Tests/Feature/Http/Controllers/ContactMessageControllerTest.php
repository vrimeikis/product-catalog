<?php

declare(strict_types = 1);

namespace Modules\ContactUs\Tests\Feature\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;
use Modules\ContactUs\Jobs\NewMessageJob;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ContactMessageControllerTest
 * @package Modules\ContactUs\Tests\Feature\Http\Controllers
 */
class ContactMessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group contact_us
     * @group api
     * @group contact_us_api
     */
    public function testSuccessPostMessage(): void
    {
        Queue::fake();

        $response = $this->post(
            route('api.contact-us.store'),
            [
                'email' => 'admin@admin.com',
                'message' => 'test_message',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertOk();

        Queue::assertPushedOn('mail', NewMessageJob::class);
    }

    /**
     * @group contact_us
     * @group api
     * @group contact_us_api
     */
    public function testFailPostNoData(): void
    {
        $response = $this->post(
            route('api.contact-us.store'),
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
