<?php

namespace Tests\Unit\ParameterDataProvider;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\IntParameterDataProvider;
use App\Models\Project;
use Tests\TestCase;

class IntParameterDataProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->intParameterDataProvider = new IntParameterDataProvider();

        $this->project = new Project();
    }

    /**
     * @dataProvider intParameterDataProvider
     */
    public function test_IntParameterDataProvider_default_return_values($dataType, $parameterName, $expectedResult)
    {
        $result = $this->intParameterDataProvider->getData($dataType, $parameterName, $this->project);

        $this->assertEquals($expectedResult, $result);
    }

    // ! start here ************************************************************************
    // TODO: Add more tests
    // test exception thrown when no apiDataType class property set
    // test each data type
    // then test class form data
    public function intParameterDataProvider()
    {
        return [
            'tinyint' => [
                'tinyint',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -128,
                        'max' => 127,
                        'maxCharacters' => 3,
                        'type' => 'number',
                    ],
                ]
            ],
            'tinyint unsigned' => [
                'tinyint unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 255,
                        'maxCharacters' => 3,
                        'type' => 'number',
                    ],
                ]
            ],
        ];
    }
}
