<?php

declare(strict_types = 1);

namespace Modules\Administration\Tests\Feature\Http\Middleware;

use Modules\Administration\Tests\Traits\AuthenticateAs;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class RouteAccessMiddlewareTest
 * @package Modules\Administration\Tests\Feature\Http\Middleware
 */
class RouteAccessMiddlewareTest extends TestCase
{
    use RefreshDatabase, AuthenticateAs;

    /**
     * @group access_middleware
     */
    public function testRestrictionAccessToUserWithNoRoles(): void
    {
        $this->authenticateAs([]);

        $this->get(route('admins.index'))
            ->assertRedirect(route('index'));
    }

    /**
     * @group access_middleware
     */
    public function testRestrictsAccessToUserWithoutRequiredRole(): void
    {
        $this->authenticateAs(['test1']);

        $this->get(route('admins.index'))
            ->assertRedirect(route('index'));
    }

    /**
     * @group access_middleware
     */
    public function testGivesAccessToUserWithRequiredRole(): void
    {
        $this->authenticateAs(['moderator'], [], ['admins.index']);

        $response = $this->get(route('admins.index'));
        $response->assertOk();
    }

    /**
     * @group access_middleware
     */
    public function testGivesAccessToUserWithFullAccess(): void
    {
        $this->authenticateAs();

        $response = $this->get(route('admins.index'));
        $response->assertOk();
    }
}
