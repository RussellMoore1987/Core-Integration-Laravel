<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CILModelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @dataProvider validationDataProvider
     */
    public function test_validateAndSave_function_class_returns_expected_error_results($data, $expectedErrors)
    {
        $project = new \App\Models\Project();
        $errors = $project->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors->toArray());
    }

    public function validationDataProvider()
    {
        return [
            // create validation test validates all the fields that are in the createValidation, create is triggered by no class id
            'error set one - create validation test' => [
                'data' => [
                    'title' => 'test',
                    'description' => 'test',
                    'is_published' => 2.2,
                ],
                'expectedData' => [
                    'roles' => [
                        'The roles field is required.',
                    ],
                    'description' => [
                        'The description must be at least 10 characters.',
                    ],
                    'start_date' => [
                        'The start date field is required.',
                    ],
                    'is_published' => [
                        'The is published must be an integer.',
                        'The is published must not be greater than 1.',
                    ],
                    'budget' => [
                        'The budget field is required.',
                    ],
                ],
            ],
            // update validation test validates only the fields that are being updated, update is triggered by class id
            'error set two - update validation test' => [
                'data' => [
                    'id' => 1,
                    'title' => 'test',
                    'description' => 'test',
                    'is_published' => 2.2,
                ],
                'expectedData' => [
                    'description' => [
                        'The description must be at least 10 characters.',
                    ],
                    'is_published' => [
                        'The is published must be an integer.',
                        'The is published must not be greater than 1.',
                    ],
                ],
            ],
        ];
    }

    // ! start here **********************************************************************
    // TODO: 
    // test also a class with no validation rules
    // test if it saves the data
    // test just the public function validate method
    /**
     * @dataProvider validationDataProvider2
     */
    public function test_validateAndSave_function_class_returns_expected_error_results_with_different_class_id($data, $expectedErrors)
    {
        $workHistoryType = new \App\Models\WorkHistoryType();
        $errors = $workHistoryType->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors->toArray());
    }

    public function validationDataProvider2()
    {
        return [
            // create validation test validates all the fields that are in the createValidation, create is triggered by no class id
            'error set one - create validation test' => [
                'data' => [
                    'name' => 't',
                    'icon' => 'test',
                ],
                'expectedData' => [
                    'name' => [
                        'The name must be at least 2 characters.'
                    ],
                ],
            ],
            // update validation test validates only the fields that are being updated, update is triggered by class id
            'error set two - update validation test' => [
                'data' => [
                    'work_history_type_id' => 1,
                    'name' => 't',
                    'icon' => 't',
                ],
                'expectedData' => [
                    'name' => [
                        'The name must be at least 2 characters.'
                    ],
                    'icon' => [
                        'The icon must be at least 2 characters.'
                    ],
                ],
            ],
        ];
    }
}
