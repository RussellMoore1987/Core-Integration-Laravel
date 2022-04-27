<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CILModelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->project = new \App\Models\Project();
    }

    // ! start here **********************************************************************
    /**
     * @dataProvider formDataProvider
     */
    public function test_validate_function_class_returns_correct_instance_of_its_self($data, $expectedData)
    {
        // dd($data, $expectedData);
        $validator = $this->project->validate($data); // updateValidation

        dd($validator->fails(), $validator->errors());

        $this->assertInstanceOf($classPath, $newClass);
    }

    public function formDataProvider()
    {
        return [
            'error set one' => [
                'data' => [
                    'title' => 'test',
                    'description' => 'test',
                    'is_published' => 2.2,
                ],
                'expectedData' => [
                    'title' => 'test',
                    'description' => 'test',
                    'is_published' => 2,
                ],
            ],
        ];
    }
}
