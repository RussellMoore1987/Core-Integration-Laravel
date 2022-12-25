<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CILModelTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createProject();
    }

    /**
     * @group db
     * @return void
     */
    public function test_validateAndSave_function_creates_record_and_then_updates_it_in_different_ways()
    {
        // just making sure that the validateAndSave function works as expected
        // create record
        $project = $this->project; // this syntax is for creating
        
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

        $project->validateAndSave($data);
        $projectComparison = Project::find($project->id);

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
        $project->validationRules = $this->project->validationRules;

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

    /**
     * @dataProvider exceptionDataProvider
     */
    public function test_validateAndSave_exception_is_thrown($validationRules)
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('validationRules rules not set. A class utilizing the CILModel trait must have validationRules, see the documentation located at app\CoreIntegrationApi\docs\CILModel.php');

        $this->project->validationRules = $validationRules;
        $this->project->validateAndSave([]);
    }

    /**
     * @dataProvider exceptionDataProvider
     */
    public function test_validate_exception_is_thrown($validationRules)
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('validationRules rules not set. A class utilizing the CILModel trait must have validationRules, see the documentation located at app\CoreIntegrationApi\docs\CILModel.php');

        $this->project->validationRules = $validationRules;
        $this->project->validate([]);
    }

    public function exceptionDataProvider()
    {
        return [
            'noValidationRules' => [null],
            'validationRulesOnlyModelValidation' => [['modelValidation' => []]],
            'validationRulesOnlyCreateValidation' => [['createValidation' => []]],
        ];
    }

    public function test_validateAndSave_method_passes_back_redirect_object()
    {
        $redirectObject = $this->project->validateAndSave(['title' => 't'], '/test/redirect');

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $redirectObject);
    }

    /**
     * @dataProvider createValidationDataProvider
     */
    public function test_validateAndSave_function_class_returns_expected_error_results_for_creates($data, $expectedErrors)
    {
        $errors = $this->project->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors);
    }

    /**
     * @dataProvider createValidationDataProvider
     */
    public function test_validate_function_class_returns_expected_error_results_for_creates($data, $expectedErrors)
    {
        // dd($this->project->validationRules);
        $validator = $this->project->validate($data);
        $errors = $validator->errors()->toArray();

        $this->assertEquals($expectedErrors, $errors);
    }

    public function createValidationDataProvider()
    {
        return [
            'App\Models\Project' => [
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
            ]
        ];
    }

    /**
     * @dataProvider updateValidationDataProvider
     * 
     * @group db
     * @return void
     */
    public function test_validateAndSave_function_class_returns_expected_error_results_for_updates($data, $expectedErrors)
    {
        $project = Project::factory()->create();
        $project->validationRules = $this->project->validationRules;
        $errors = $project->validateAndSave($data);

        $this->assertEquals($expectedErrors, $errors);
    }

    /**
     * @dataProvider updateValidationDataProvider
     */
    public function test_validate_function_class_returns_expected_error_results_for_updates($data, $expectedErrors)
    {
        $project = Project::factory()->create();
        $project->validationRules = $this->project->validationRules;
        $validator = $project->validate($data);
        $errors = $validator->errors()->toArray();

        $this->assertEquals($expectedErrors, $errors);
    }

    public function updateValidationDataProvider()
    {
        return [
            'App\Models\Project' => [
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
            'App\Models\Project - no data sent' => [ // saves with db defaults
                'data' => [],
                'expectedData' => [],
            ],
        ];
    }

    protected function createProject()
    {
        $this->project = new Project();

        $this->project->validationRules = [
            'modelValidation' => [
                'id' => [
                    'integer',
                    'min:1',
                    'max:18446744073709551615',
                ],
                'title' => [
                    'string',
                    'max:75',
                    'min:2',
                ],
                'roles' => [
                    'string',
                    'max:50',
                    'nullable',
                ],
                'client' => [
                    'string',
                    'max:50',
                    'nullable',
                ],
                'description' => [
                    'string',
                    'max:255',
                    'min:10',
                    'nullable',
                ],
                'content' => [
                    'string',
                    'json',
                    'nullable',
                ],
                'video_link' => [
                    'string',
                    'max:255',
                    'nullable',
                ],
                'code_link' => [
                    'string',
                    'max:255',
                    'nullable',
                ],
                'demo_link' => [
                    'string',
                    'max:255',
                    'nullable',
                ],
                'start_date' => [
                    'date',
                    'nullable',
                ],
                'end_date' => [
                    'date',
                    'nullable',
                ],
                'is_published' => [
                    'integer',
                    'min:0',
                    'max:1',
                ],
                'budget' => [
                    'numeric',
                    'max:999999.99',
                    'min:0',
                ],
            ],
            'createValidation' => [
                'title' => [
                    'required',
                ],
                'roles' => [
                    'required',
                ],
                'description' => [
                    'required',
                ],
                'start_date' => [
                    'required',
                ],
                'budget' => [
                    'required',
                ],
            ],
        ];
    }
}
