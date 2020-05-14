<?php

declare(strict_types = 1);

namespace Modules\ContactUs\Tests\Unit\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\ContactUs\Entities\ContactMessage;
use Modules\ContactUs\Services\ContactMessageService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactMessageServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group contact_us
     * @group service
     * @group contact_us_service
     * @throws BindingResolutionException
     */
    public function testStoreData(): void
    {
        /** @var ContactMessage $data */
        $data = factory(ContactMessage::class)->make();

        $contactMessage = $this->getTestClassInstance()->storeData($data->toArray());

        $this->assertInstanceOf(ContactMessage::class, $contactMessage);
        $this->assertDatabaseHas('contact_messages', $data->toArray());
    }

    /**
     * @return ContactMessageService
     * @throws BindingResolutionException
     */
    private function getTestClassInstance(): ContactMessageService
    {
        return $this->app->make(ContactMessageService::class);
    }
}
