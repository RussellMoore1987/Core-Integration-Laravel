<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use Tests\TestCase;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub DateResourceParameterInfoProvider)
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test
// 'date' => ['date'],
// 'timestamp' => ['Timestamp'],
// 'datetime' => ['datetime'],
// 'year' => ['year'],
// add different/specific exceptions to past tests, like ResourceParameterInfoProviderException
// *** look over changes made, when tired

class DateResourceParameterInfoProviderTest extends TestCase
{
    protected $project;
    protected $dateResourceParameterInfoProvider;
    protected $expectedResult;
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
     */
    public function test_DateResourceParameterInfoProvider_default_return_values($parameterDataType, $expectedResult): void
    {
        $this->parameterInfo['type'] = $parameterDataType;

        $result = $this->dateResourceParameterInfoProvider->getData($this->parameterInfo, []);

        $this->assertEquals($expectedResult, $result);
    }

    public function dateResourceParameterInfoProvider(): array
    {
        return [
            'datetime' => [
                'datetime',
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1000-01-01 00:00:00',
                        'max' => '9999-12-31 23:59:59',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1000-01-01 00:00:00',
                        'before_or_equal:9999-12-31 23:59:59',
                    ],
                ],
            ],
            'timestamp' => [
                'timestamp',
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
            ],
            'year' => [
                'year',
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1901',
                        'max' => '2155',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1901',
                        'before_or_equal:2155',
                    ],
                ],
            ],
            'date' => [
                'date',
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1000-01-01',
                        'max' => '9999-12-31',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1000-01-01',
                        'before_or_equal:9999-12-31',
                    ],
                ],
            ],
        ];
    }
}
