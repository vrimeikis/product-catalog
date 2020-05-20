<?php

declare(strict_types = 1);

namespace Modules\Product\Tests\Unit\Helpers;

use Modules\Product\Helpers\MathHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class MathSimpleHelperTest
 * @package Modules\Product\Tests\Unit\Helpers
 */
class MathSimpleHelperTest extends TestCase
{
    /**
     * @group helpers
     * @group math
     */
    public function testPercent1(): void
    {
        $result = MathHelper::percent(1000, 700);

        $this->assertEquals(70, $result);
    }

    /**
     * @group helpers
     * @group math
     */
    public function testPercent2(): void
    {
        $result = MathHelper::percent(1000, 600);

        $this->assertEquals(60, $result);
    }

    /**
     * @group helpers
     * @group math
     */
    public function testPercent3(): void
    {
        $result = MathHelper::percent(0, 600);

        $this->assertEquals(0, $result);
    }

    /**
     * @group helpers
     * @group math
     */
    public function testPercent4(): void
    {
        $result = MathHelper::percent(110, 0);

        $this->assertEquals(0, $result);
    }

    /**
     * @group helpers
     * @group math
     */
    public function testPercent5(): void
    {
        $result = MathHelper::percent(1, 0.55);

        $this->assertEquals(55, $result);
    }

    /**
     * @group helpers
     * @group math
     */
    public function testPercent6(): void
    {
        $result = MathHelper::percent(10, 20);

        $this->assertEquals(200, $result);
    }
}
