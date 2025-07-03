<?php

namespace Tests\Unit\FunctionalityProviders;

use App\CoreIntegrationApi\FunctionalityProviders\Helper;
use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * @dataProvider resourceIdProvider
     * @group get
     * @group rest
     */
    public function test_isSingleRestIdRequest_returns_correct_results($resourceId, bool $expectedResponse): void
    {
        $this->assertEquals($expectedResponse, Helper::isSingleRestIdRequest($resourceId));
    }

    public function resourceIdProvider(): array
    {
        return [
            'single id' => ['1', true],
            'multiple ids' => ['1,2', false],
            'single id with action' => ['1::in', false],
            'multiple ids with action' => ['1,2,3::in', false],
            'single text id' => ['gjdie753jd', true],
            'multiple text ids' => ['gjdie753jd,hdj3j', false],
            'single text id with action' => ['gjdie753jd::in', false],
            'comma not pass' => [',', false],
            'double colon not pass' => ['::', false],
            'single colon in id' => ['fsDf:fvVb453', true],
        ];
    }
}
