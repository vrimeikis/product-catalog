<?php

declare(strict_types = 1);

namespace Modules\Core\Tests\Unit;

use Faker\Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        // #1
        $this->assertCount($count, $testRepository->all());

        // #2
        $testRepository->all()->each(function (User $user) use ($users, &$i) {
            $factoryUser = $users->get($i++);

            foreach (array_keys($user->getAttributes()) as $attribute) {
                $this->assertEquals($factoryUser->$attribute, $user->$attribute);
            }
        });

        $i = 0;
        // #3
        $testRepository->all(['name'])->each(function (User $user) use ($users, &$i) {
            /** @var User $factoryUser */
            $factoryUser = $users->get($i++);

            $this->assertEquals($factoryUser->name, $user->name);
            $this->assertEquals(['name'], array_keys($user->getAttributes()));
        });
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testFindReturnNullNonExistsRecord(): void
    {
        $id = mt_rand(1, 10);

        $this->assertNull($this->getTestClassInstance()->find($id));
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testFindReturnFilledModel(): void
    {
        factory(User::class, 2)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        factory(User::class, 3)->create();

        $dbUser = $this->getTestClassInstance()->find($user->id);

        $this->assertNotNull($dbUser);
        $this->assertInstanceOf(User::class, $dbUser);
        $this->assertEquals($user->getAttributes(), $dbUser->getAttributes());
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testFindWithRelation(): void
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create([
            'role_id' => $role->id,
        ]);

        $result = $this->getTestClassInstance()->with(['role'])->find($user->id);

        $this->assertTrue($result->relationLoaded('role'));
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testFindOrFailThrowException(): void
    {
        $id = mt_rand(1, 10);

        $this->expectException(ModelNotFoundException::class);
        $this->getTestClassInstance()->findOrFail($id);
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testFindOrFailReturnModel(): void
    {
        factory(User::class, 3)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $dbUser = $this->getTestClassInstance()->findOrFail($user->id);

        $this->assertNotNull($dbUser);
        $this->assertInstanceOf(User::class, $dbUser);
        $this->assertEquals($user->getAttributes(), $dbUser->getAttributes());
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testPluckOnEmptyTable(): void
    {
        $result = $this->getTestClassInstance()->pluck('name');

        $this->assertEmpty($result);
        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testPluckSetOnlyColumn(): void
    {
        /** @var Collection $users */
        $users = factory(User::class, 3)->create();

        $result = $this->getTestClassInstance()->pluck('name');

        $this->assertEquals(
            $users->pluck('name'),
            $result
        );
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testPluckSetColumnAndKey(): void
    {
        /** @var Collection $users */
        $users = factory(User::class, 3)->create();
        $result = $this->getTestClassInstance()->pluck('name', 'id');

        $this->assertEquals(
            $users->pluck('name', 'id'),
            $result
        );
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testCreate(): void
    {
        $data = [
            'name' => 'test_name',
            'email' => 'test_email',
            'password' => 'secret',
        ];

        /** @var User $fakeUser */
        $fakeUser = $this->getTestClassInstance()->create($data);

        $this->assertInstanceOf(User::class, $fakeUser);
        $this->assertEquals($data['name'], $fakeUser->name);
        $this->assertEquals($data['email'], $fakeUser->email);
        $this->assertEquals($data['password'], $fakeUser->password);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testPaginateOnEmptyTable(): void
    {
        $paginateResult = $this->getTestClassInstance()->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginateResult);
        $this->assertTrue($paginateResult->isEmpty());
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testPaginateNonEmptyTable(): void
    {
        $numberPerPage = mt_rand(1, 30);
        $count = mt_rand(2, 100);

        /** @var Collection|User[] $users */
        $users = factory(User::class, $count)->create();

        /** @var LengthAwarePaginator|User[] $paginator */
        $paginator = $this->getTestClassInstance()->paginate($numberPerPage);

        $this->assertEquals($users[0]->toArray(), $paginator[0]->toArray());
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertEquals($numberPerPage, $paginator->perPage());
        $this->assertEquals($count, $paginator->total());
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testUpdate(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'name' => 'Denis'
        ]);

        $this->getTestClassInstance()->update(['name' => 'John'], $user->id);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'John']);
        $this->assertDatabaseMissing('users', ['id' => $user->id, 'name' => 'Denis']);
    }

    /**
     * @group repository
     * @throws BindingResolutionException
     */
    public function testDelete(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $otherUser */
        $otherUser = factory(User::class)->create();

        $this->getTestClassInstance()->delete($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $otherUser->id]);
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
 * @property int $id
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
        'remember_token',
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
 * @property int $id
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
