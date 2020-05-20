<?php

declare(strict_types=1);

namespace Modules\Product\Tests\Unit\Helpers;

use Modules\Product\Helpers\MathHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MathHelperTest extends TestCase
{
    /**
     * @group helpers
     * @group math1
     *
     * @dataProvider percentMathDataProvider
     * @param float $firstData
     * @param float $secondData
     * @param float $expected
     */
    public function testPercent(float $firstData, float $secondData, float $expected): void
    {
        $result = MathHelper::percent($firstData, $secondData);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function percentMathDataProvider(): array
    {
        return [
            'Test 70%' => [
                'firstData' => 1000,
                'secondData' => 700,
                'expected' => 70,
            ],
            'Test 60%' => [
                'firstData' => 1000,
                'secondData' => 600,
                'expected' => 60,
            ],
            'Test first zero' => [
                'firstData' => 0,
                'secondData' => 600,
                'expected' => 0,
            ],
            'Test second zero' => [
                'firstData' => 110,
                'secondData' => 0,
                'expected' => 0,
            ],
            'Test float data' => [
                'firstData' => 1,
                'secondData' => 0.55,
                'expected' => 55,
            ],
            'Test first is less' => [
                'firstData' => 10,
                'secondData' => 20,
                'expected' => 200,
            ],
        ];
    }
}
