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
    public function test_validateAndSave_function_create_and_then_update_in_different_ways()
    {
        // just making sure that the validateAndSave function works as expected
        // create
        $project = new Project(); // this syntax is for creating

        $titleText = 'test1';
        $rolesText = 'test1';
        $descriptionText = 'test5678910-1';
        $startDate = '2020-12-21 00:00:00';
        $isPublishedNumber = 1;
        $budgetNumber = 2222.33;

        $data = [
            'title' => $titleText, // required
            'roles' => $rolesText, // required
            'description' => $descriptionText, // required
            'start_date' => $startDate, // required
            'is_published' => $isPublishedNumber,
            'budget' => $budgetNumber, // required
            'test_field' => 'test_value', // extra field should be ignored
        ];

        $project->validateAndSave($data);

        $projectComparison = Project::find($project->id);

        $this->assertEquals($titleText, $projectComparison->title);
        $this->assertEquals($rolesText, $projectComparison->roles);
        $this->assertEquals($descriptionText, $projectComparison->description);
        $this->assertEquals($startDate, $projectComparison->start_date);
        $this->assertEquals($isPublishedNumber, $projectComparison->is_published);
        $this->assertEquals($budgetNumber, $projectComparison->budget);

        // update version 1 - using the created model
        $titleText = 'test2';
        $rolesText = 'test2';
        $isPublishedNumber = 0;
        $budgetNumber = 3333.22;

        $data = [
            'title' => $titleText,
            'roles' => $rolesText,
            'is_published' => $isPublishedNumber,
            'budget' => $budgetNumber,
        ];

        $projectComparison->validateAndSave($data);

        $this->assertEquals($titleText, $projectComparison->title);
        $this->assertEquals($rolesText, $projectComparison->roles);
        $this->assertEquals($descriptionText, $projectComparison->description);
        $this->assertEquals($startDate, $projectComparison->start_date);
        $this->assertEquals($isPublishedNumber, $projectComparison->is_published);
        $this->assertEquals($budgetNumber, $projectComparison->budget);

        // update version 2 - finding the model and then updating
        // changing just a few fields and making sure that the other fields are not changed
        $titleText = 'test3';
        $rolesText = 'test3';

        $project = Project::find($project->id);

        $data = [
            'title' => $titleText,
            'roles' => $rolesText,
        ];

        $project->validateAndSave($data);

        $newProjectComparison = Project::find($project->id);

        $this->assertEquals($titleText, $newProjectComparison->title);
        $this->assertEquals($rolesText, $newProjectComparison->roles);
        $this->assertEquals($descriptionText, $newProjectComparison->description);
        $this->assertEquals($startDate, $newProjectComparison->start_date);
        $this->assertEquals($isPublishedNumber, $newProjectComparison->is_published);
        $this->assertEquals($budgetNumber, $newProjectComparison->budget);
    }

    public function test_validateAndSave_exception_is_thrown()
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('validationRules rules not set. A class utilizing the CILModel trait must have validationRules, see the documentation located at app\CoreIntegrationApi\docs\CILModel.php');

        $tag = new \App\Models\Tag();
        $tag->validateAndSave([]);
    }

    public function test_validate_exception_is_thrown()
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('validationRules rules not set. A class utilizing the CILModel trait must have validationRules, see the documentation located at app\CoreIntegrationApi\docs\CILModel.php');

        $tag = new \App\Models\Tag();
        $tag->validate([]);
    }

    /**
     * @dataProvider createValidationDataProvider
     */
    public function test_validateAndSave_function_class_returns_expected_error_results_for_creates($classPath, $data, $expectedErrors)
    {
        $class = new $classPath();
        $errors = $class->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors);
    }

    /**
     * @dataProvider createValidationDataProvider
     */
    public function test_validate_function_class_returns_expected_error_results_for_creates($classPath, $data, $expectedErrors)
    {
        $class = new $classPath();
        $validator = $class->validate($data);
        $errors = $validator->errors()->toArray();

        $this->assertEquals($expectedErrors, $errors);
    }

    public function createValidationDataProvider()
    {
        return [
            'App\Models\Project' => [
                'classPath' => 'App\Models\Project',
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
            'App\Models\Project - no data sent' => [
                'classPath' => 'App\Models\Project',
                'data' => [],
                'expectedData' => [
                    'title' => [
                        'The title field is required.',
                    ],
                    'roles' => [
                        'The roles field is required.',
                    ],
                    'description' => [
                        'The description field is required.',
                    ],
                    'start_date' => [
                        'The start date field is required.',
                    ],
                    'budget' => [
                        'The budget field is required.',
                    ],
                ],
            ],
            'App\Models\WorkHistoryType' => [
                'classPath' => 'App\Models\WorkHistoryType',
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
            
        ];
    }

    /**
     * @dataProvider updateValidationDataProvider
     * 
     * @group db
     * @return void
     */
    public function test_validateAndSave_function_class_returns_expected_error_results_for_updates($classPath, $data, $expectedErrors)
    {
        $class = $classPath::factory()->create();
        $errors = $class->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors);
    }

    /**
     * @dataProvider updateValidationDataProvider
     * 
     * @group db
     * @return void
     */
    public function test_validate_function_class_returns_expected_error_results_for_updates($classPath, $data, $expectedErrors)
    {
        $class = $classPath::factory()->create();
        $validator = $class->validate($data);
        $errors = $validator->errors()->toArray();

        $this->assertEquals($expectedErrors, $errors);
    }

    public function updateValidationDataProvider()
    {
        return [
            'App\Models\Project' => [
                'classPath' => 'App\Models\Project',
                'data' => [
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
            'App\Models\Project - no data sent' => [
                'classPath' => 'App\Models\Project',
                'data' => [],
                'expectedData' => [],
            ],
            'App\Models\WorkHistoryType' => [
                'classPath' => 'App\Models\WorkHistoryType',
                'data' => [
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
