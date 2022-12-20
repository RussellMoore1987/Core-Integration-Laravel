<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use App\Models\Project;
use Tests\TestCase;

class DateResourceParameterInfoProviderTest extends TestCase
{
    protected $project;
    protected $dateResourceParameterInfoProvider;
    protected $expectedResult;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dateResourceParameterInfoProvider = new DateResourceParameterInfoProvider();

        $this->project = new Project();
    }

    /**
     * @dataProvider dateResourceParameterInfoProvider
     */
    public function test_DateResourceParameterInfoProvider_default_return_values($parameterDataInfo, $expectedResult)
    {
        unset($this->project->formData);
        unset($this->project->validationRules);
        $result = $this->dateResourceParameterInfoProvider->getData($parameterDataInfo, $this->project);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @dataProvider dateResourceParameterInfoProvider
     */
    public function test_DateResourceParameterInfoProvider_default_return_values_with_requires($parameterDataInfo, $expectedResult)
    {
        unset($this->project->formData);
        unset($this->project->validationRules);
        $parameterDataInfo['default'] = null;
        $expectedResult['formData']['required'] = true;
        $expectedResult['defaultValidationRules'][] = 'required';

        $result = $this->dateResourceParameterInfoProvider->getData($parameterDataInfo, $this->project);

        $this->assertEquals($expectedResult, $result);
    }

    public function dateResourceParameterInfoProvider()
    {
        return [
            'datetime' => [
                [
                    'field' => 'fakeParameterName',
                    'type' => 'datetime',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'timestamp',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'year',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'date',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
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

    /**
     * @dataProvider classFormDataProvider
     */
    public function test_DateResourceParameterInfoProvider_with_class_form_data_returned($parameterDataInfo, $formData, $expectedResult)
    {
        $this->project->formData = [
            'fakeParameterName' => $formData,
        ];
        
        $this->expectedResult = $expectedResult;

        $result = $this->dateResourceParameterInfoProvider->getData($parameterDataInfo,  $this->project);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function classFormDataProvider()
    {
        return [
            'datetime' => [
                [
                    'field' => 'fakeParameterName',
                    'type' => 'datetime',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
                [],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'timestamp',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
                [
                    'min' => '1979-01-01 00:00:01',
                    'max' => '2030-01-19 23:59:59',
                    'extra' => 'extra',
                ],
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1979-01-01 00:00:01',
                        'max' => '2030-01-19 23:59:59',
                        'extra' => 'extra',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
            ],
            'year' => [
                [
                    'field' => 'fakeParameterName',
                    'type' => 'year',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
                [
                    'max' => '2050',
                ],
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1901',
                        'max' => '2050',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1901',
                        'before_or_equal:2155',
                    ],
                ],
            ],
            'date' => [
                [
                    'field' => 'fakeParameterName',
                    'type' => 'date',
                    'null' => 'no',
                    'key' => '',
                    'default' => '2022-05-17 00:00:00',
                    'extra' => '',
                ],
                [
                    'min' => '1979-01-01',
                    'max' => '2050-12-31',
                    'type' => 'datePicker',
                ],
                [
                    'apiDataType' => 'date',
                    'formData' => [
                        'min' => '1979-01-01',
                        'max' => '2050-12-31',
                        'type' => 'datePicker',
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
