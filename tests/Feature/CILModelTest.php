<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class CILModelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @group db
     * @return void
     */
    public function test_validateAndSave_function_create_and_then_update()
    {
        // create
        $project = new Project();

        $data = [
            'title' => 'test1', // required
            'roles' => 'test1', // required
            'description' => 'test5678910-1', // required
            'start_date' => '2020-12-21 00:00:00', // required
            'is_published' => 1,
            'budget' => 2222.33, // required
        ];

        $errors = $project->validateAndSave($data);

        $projectComparison = Project::find($project->id);

        $this->assertEquals('test1', $projectComparison->title);
        $this->assertEquals('test1', $projectComparison->roles);
        $this->assertEquals('test5678910-1', $projectComparison->description);
        $this->assertEquals('2020-12-21 00:00:00', $projectComparison->start_date);
        $this->assertEquals(1, $projectComparison->is_published);
        $this->assertEquals(2222.33, $projectComparison->budget);

        // update
        $data = [
            'title' => 'test2', 
            'roles' => 'test2', 
            'is_published' => 0,
            'budget' => 3333.22, 
        ];

        $projectComparison->validateAndSave($data);

        $this->assertEquals('test2', $projectComparison->title);
        $this->assertEquals('test2', $projectComparison->roles);
        $this->assertEquals('test5678910-1', $projectComparison->description);
        $this->assertEquals('2020-12-21 00:00:00', $projectComparison->start_date);
        $this->assertEquals(0, $projectComparison->is_published);
        $this->assertEquals(3333.22, $projectComparison->budget);
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function test_validateAndSave_function_class_returns_expected_error_results($data, $expectedErrors)
    {
        $project = new \App\Models\Project();
        $errors = $project->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors);
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

        $this->assertEquals($expectedErrors, $errors);
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

    /**
     * @dataProvider validationDataProvider3
     * 
     * @group db
     * @return void
     */
    public function test_validateAndSave_function_class_returns_expected_error_results_with_uncompleted_validation($classPath, $data, $expectedErrors)
    {
        // $class = new \App\Models\$class();
        $class = App::make($classPath);
        $errors = $class->validateAndSave($data);
        
        $this->assertEquals($expectedErrors, $errors);
    }

    public function validationDataProvider3()
    {
        return [
            // create validation test validates all the fields that are in the createValidation, create is triggered by no class id
            // saves a new record with db default values, no validation accrued
            'error set one - create validation test with uncompleted validation' => [
                'classPath' => 'App\Models\CaseStudy',
                'data' => [
                    'test' => 1,
                    'title' => 't',
                ],
                'expectedData' => [],
            ],
            
            // update validation test validates only the fields that are being updated, update is triggered by class id
            'error set one - update validation test with uncompleted validation' => [
                'classPath' => 'App\Models\CaseStudy',
                'data' => [
                    'id' => 1,
                    'title' => 'test',
                ],
                'expectedData' => [],
            ],




            // 'error set two - update validation test' => [
            //     'classPath' => 'App\Models\Skill',
            //     'data' => [
            //         'work_history_type_id' => 1,
            //         'name' => 't',
            //         'icon' => 't',
            //     ],
            //     'expectedData' => [
            //         'name' => [
            //             'The name must be at least 2 characters.'
            //         ],
            //         'icon' => [
            //             'The icon must be at least 2 characters.'
            //         ],
            //     ],
            // ],
        ];
    }
}
