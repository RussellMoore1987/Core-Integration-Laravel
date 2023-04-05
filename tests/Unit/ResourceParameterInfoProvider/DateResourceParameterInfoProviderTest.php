<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use Tests\TestCase;

class DateResourceParameterInfoProviderTest extends TestCase
{
    protected $dateResourceParameterInfoProvider;
    protected $parameterInfo = [
        'field' => 'fakeParameterName',
        'type' => 'datetime',
        'null' => 'no',
        'key' => '',
        'default' => '2022-05-17 00:00:00',
        'extra' => '',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->dateResourceParameterInfoProvider = new DateResourceParameterInfoProvider();
    }

    /**
     * @dataProvider dateResourceParameterInfoProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_DateResourceParameterInfoProvider_return_default_values($parameterDataType, $expectedResultPieces): void
    {
        $this->parameterInfo['type'] = $parameterDataType;

        $result = $this->dateResourceParameterInfoProvider->getData($this->parameterInfo, []);

        $this->assertEquals($this->getExpectedResult($expectedResultPieces), $result);
    }

    public function dateResourceParameterInfoProvider(): array
    {
        return [
            'datetime' => [
                'datetime',
                [
                    'min' => '1000-01-01 00:00:00',
                    'max' => '9999-12-31 23:59:59',
                    'defaultValidationRules' => [
                        'after_or_equal:1000-01-01 00:00:00',
                        'before_or_equal:9999-12-31 23:59:59',
                    ],
                ],
            ],
            'timestamp' => [
                'timestamp',
                [
                    'min' => '1970-01-01 00:00:01',
                    'max' => '2038-01-19 03:14:07',
                    'defaultValidationRules' => [
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
            ],
            'year' => [
                'year',
                [
                    'min' => '1901',
                    'max' => '2155',
                    'defaultValidationRules' => [
                        'after_or_equal:1901',
                        'before_or_equal:2155',
                    ],
                ],
            ],
            'date' => [
                'date',
                [
                    'min' => '1000-01-01',
                    'max' => '9999-12-31',
                    'defaultValidationRules' => [
                        'after_or_equal:1000-01-01',
                        'before_or_equal:9999-12-31',
                    ],
                ],
            ],
        ];
    }

    protected function getExpectedResult(array $expectedResultPieces): array
    {
        return [
            'apiDataType' => 'date',
            'formData' => [
                'type' => 'date',
                'min' => $expectedResultPieces['min'],
                'max' => $expectedResultPieces['max'],
            ],
            'defaultValidationRules' => [
                'date',
                $expectedResultPieces['defaultValidationRules'][0],
                $expectedResultPieces['defaultValidationRules'][1]
            ]
        ];
    }
}
