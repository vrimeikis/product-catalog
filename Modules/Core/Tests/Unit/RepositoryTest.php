<?php

declare(strict_types = 1);

namespace Modules\Core\Tests\Unit;

use Faker\Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Modules\Core\Repositories\Repository;
use Modules\Core\Tests\RepositoryTestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class RepositoryTest
 * @package Modules\Core\Tests\Unit
 */
class RepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory->define(User::class, function (Generator $faker) {
            static $password;

            return [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => $password ?: $password = bcrypt('secret'),
                'remember_token' => $faker->randomDigitNotNull,
                'role_id' => null,
            ];
        });

        $this->factory->define(Role::class, function (Generator $faker) {
            return [
                'name' => $faker->name,
            ];
        });
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testModelMethodReturnBinnedModelClass(): void
    {
        $this->assertEquals(User::class, $this->getTestClassInstance()->model());
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testAllReturnCollectionInstance(): void
    {
        $this->assertInstanceOf(
            Collection::class,
            $this->getTestClassInstance()->all()
        );
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testAllReturnSelectedColumns(): void
    {
        $count = mt_rand(2, 10);
        $i = 0;

        $users = factory(User::class, $count)->create();
        $testRepository = $this->getTestClassInstance();

        $this->assertCount($count, $testRepository->all());

        $testRepository->all()->each(function (User $user) use ($users, &$i) {
            $factoryUser = $users->get($i++);

            foreach (array_keys($user->getAttributes()) as $attribute) {
                $this->assertEquals($factoryUser->$attribute, $user->$attribute);
            }
        });

        $i = 0;
        $testRepository->all(['name'])->each(function (User $user) use ($users, &$i) {
            /** @var User $factoryUser */
            $factoryUser = $users->get($i++);

            $this->assertEquals($factoryUser->name, $user->name);
            $this->assertEquals(['name'], array_keys($user->getAttributes()));
        });
    }

    /**
     * @return RepositoryFake
     * @throws BindingResolutionException
     */
    private function getTestClassInstance(): RepositoryFake
    {
        return $this->app->make(RepositoryFake::class);
    }
}

/**
 * Class User
 * @package Modules\Core\Tests\Unit
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int|null $role_id
 * @property-read Role|null $role
 */
class User extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}

/**
 * Class Role
 * @package Modules\Core\Tests\Unit
 * @property string $name
 */
class Role extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];
}

/**
 * Class RepositoryFake
 * @package Modules\Core\Tests\Unit
 */
class RepositoryFake extends Repository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }
}
