<?php

declare(strict_types = 1);

namespace Modules\Core\Tests;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Throwable;

/**
 * Class RepositoryTestCase
 * @package Modules\Core\Tests
 */
class RepositoryTestCase extends TestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->app->make(Factory::class);

        $this->migrate();
    }

    /**
     * @return void
     * @throws Throwable
     */
    protected function tearDown(): void
    {
        $this->dropTables();

        parent::tearDown();
    }

    /**
     * @return void
     */
    private function migrate(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * @return void
     */
    private function dropTables(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}