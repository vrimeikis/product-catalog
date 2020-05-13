<?php

declare(strict_types = 1);

namespace Modules\Core\Tests\Unit;

use Modules\Core\Enum\Enumerable;
use Modules\Core\Exceptions\EnumNotFoundException;
use ReflectionException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class EnumerableTest
 * @package Modules\Core\Tests\Unit
 */
class EnumerableTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testIdAndNameGettersReturnCorrectData(): void
    {
        $enum = TestEnum::testCase();

        $this->assertEquals('test_id', $enum->id());
        $this->assertEquals('test_name', $enum->name());
    }

    /**
     * @throws ReflectionException
     */
    public function testReturnDescriptionGetter(): void
    {
        $this->assertEquals('Test Case Description', TestEnum::testCaseWithDescription()->description());
        $this->assertEquals('', TestEnum::testCase()->description());
    }

    /**
     * @throws ReflectionException
     */
    public function testSameTwoEnumCases(): void
    {
        $enumOne = TestEnum::testCase();
        $enumTwo = TestEnum::testCase();

        $this->assertSame($enumOne, $enumTwo);
    }

    /**
     * @throws ReflectionException
     */
    public function testReturnOnlyFinalPublicStaticMethods(): void
    {
        $enum = TestEnum::enum();

        $this->assertArrayHasKey('test_id', $enum);
        $this->assertArrayHasKey('test_id_2', $enum);
        $this->assertArrayNotHasKey('random_id', $enum);

        $this->assertSame(TestEnum::testCase(), $enum['test_id']);
        $this->assertSame(TestEnum::testCaseTwo(), $enum['test_id_2']);
    }

    /**
     * @throws ReflectionException
     * @throws EnumNotFoundException
     */
    public function testCanCreateCasesFromId(): void
    {
        $this->assertSame(TestEnum::testCase(), TestEnum::from('test_id'));
        $this->assertSame(TestEnum::testCaseTwo(), TestEnum::from('test_id_2'));
    }

    /**
     * @throws EnumNotFoundException
     * @throws ReflectionException
     */
    public function testThrowExceptionOnTryingGetNonExistingEnum(): void
    {
        $this->expectException(EnumNotFoundException::class);
        $this->expectExceptionMessage('Unable to find enumerable with test_enum of type '. TestEnum::class);

        TestEnum::from('test_enum');
    }
}

/**
 * Class TestEnum
 * @package Modules\Core\Tests\Unit
 */
class TestEnum extends Enumerable
{
    /**
     * @return TestEnum
     * @throws ReflectionException
     */
    final public static function testCase(): TestEnum
    {
        return self::make('test_id', 'test_name');
    }

    /**
     * @return TestEnum
     * @throws ReflectionException
     */
    final public static function testCaseTwo(): TestEnum
    {
        return self::make('test_id_2', 'test_name_2');
    }

    /**
     * @return TestEnum
     * @throws ReflectionException
     */
    final public static function testCaseWithDescription(): TestEnum
    {
        return self::make('test_id_d', 'Description Name', 'Test Case Description');
    }

    /**
     * @return TestEnum
     * @throws ReflectionException
     */
    public static function randomMethod(): TestEnum
    {
        return self::make('random_id', 'random_name');
    }
}