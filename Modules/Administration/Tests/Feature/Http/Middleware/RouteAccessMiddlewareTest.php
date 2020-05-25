<?php

declare(strict_types = 1);

namespace Modules\Administration\Tests\Feature\Http\Middleware;

use App\Admin;
use App\Roles;
use Modules\Administration\Tests\Traits\AuthenticateAs;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
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
        /** @var Roles $role */
        $role = factory(Roles::class)->state('moderator')->create([
            'accessible_routes' => ['admins.index'],
        ]);
        /** @var Admin $admin */
        $admin = factory(Admin::class)->create();
        $admin->roles()->sync([$role->id]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admins.index'));
        $response->assertOk();
    }

    /**
     * @group access_middleware
     */
    public function testGivesAccessToUserWithFullAccess(): void
    {
        /** @var Roles $role */
        $role = factory(Roles::class)->state('super_admin')->create();
        /** @var Admin $admin */
        $admin = factory(Admin::class)->create();
        $admin->roles()->sync([$role->id]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admins.index'));
        $response->assertOk();
    }
}
