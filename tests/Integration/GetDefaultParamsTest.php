<?php

namespace Tests\Integration;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetDefaultParamsTest extends TestCase
{
    use DatabaseTransactions;

    private Project $project1;
    private Project $project2;
    private Project $project3;
    private Project $project4;
    private Project $project5;

    /**
     * @dataProvider perPageProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_per_page_default_pram_works_as_expected(string $perPageString): void
    {
        $this->createProjects();

        $response = $this->get("/api/v1/projects/?{$perPageString}=2&fullInfo=true");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals(2, $responseArray['acceptedParameters']['perPage']);
        $this->assertEquals(1, $responseArray['current_page']);
        $this->assertEquals(3, $responseArray['last_page']);
        $this->assertEquals(2, $responseArray['per_page']);
        $this->assertEquals(1, $responseArray['from']);
        $this->assertEquals(2, $responseArray['to']);
        $this->assertEquals(5, $responseArray['total']);
    }

    public function perPageProvider(): array
    {
        return [
            'perPage' => ['perPage'],
            'perpagE' => ['perpagE'], // showing case insensitivity
            'per_page' => ['per_page'],
            'pEr_Page' => ['pEr_Page'], // showing case insensitivity
        ];
    }

    /**
     * @dataProvider pageProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_page_default_pram_works_as_expected(string $pageString): void
    {
        $this->createProjects();

        $response = $this->get("/api/v1/projects/?perPage=2&{$pageString}=3&fullInfo=true");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals(3, $responseArray['current_page']);
        $this->assertEquals(3, $responseArray['last_page']);
        $this->assertEquals(2, $responseArray['per_page']);
        $this->assertEquals(5, $responseArray['from']);
        $this->assertEquals(5, $responseArray['to']);
        $this->assertEquals(5, $responseArray['total']);
        $this->assertCount(1, $responseArray['data']);
    }

    public function pageProvider(): array
    {
        return [
            'page' => ['page'],
            'pAge' => ['pAge'], // showing case insensitivity
        ];
    }

    /**
     * @dataProvider parameterValueProvider
     * @group get
     * @group rest
     */
    public function test_get_request_returns_expected_result_default_parameters_rejected($pageValue, $perPageValue): void
    {
        $response = $this->get("/api/v1/projects?page={$pageValue}&perPage={$perPageValue}");

        $response->assertStatus(422);

        $responseArray = json_decode($response->content(), true);

        $expectedResponse = [
            'error' => 'Validation Failed',
            'rejectedParameters' => [
                'page' => [
                    'value' => $pageValue,
                    'parameterError' => "This parameter's value must be an int.",
                ],
                'perPage' => [
                    'value' => $perPageValue,
                    'parameterError' => "This parameter's value must be an int.",
                ],
            ],
            'acceptedParameters' => [
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost:8000/api/v1/'
                ]
            ],
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'statusCode' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function parameterValueProvider(): array
    {
        return [
            'float values' => [2.6, 22.2],
            'string values' => ['sam', 'fun'],
        ];
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_page_x_is_not_there_exception(): void
    {
        $this->createProjects();

        $response = $this->get('/api/v1/projects/?perPage=2&page=4');

        $response->assertStatus(422);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals([
            'error' => 'Default page parameter is invalid',
            'message' => 'The page parameter is too high for the current data set. The last page is 3 and you requested page 4.',
            'statusCode' => 422
          ], $responseArray
        );
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_page_or_per_page_does_not_affect_single_id_request(): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/{$this->project3->id}/?page=4&perPage=0");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals($this->project3->id, $responseArray['id']);
        $this->assertEquals($this->project3->title, $responseArray['title']);
    }

    /**
     * @dataProvider columnProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_columnData_returns_just_the_endpoints_column_data(string $url, string $column, string $value): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/{$url}/?{$column}={$value}");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals([
            'id' => 'int',
            'title' => 'string',
            'roles' => 'string',
            'client' => 'string',
            'description' => 'string',
            'content' => 'json',
            'video_link' => 'string',
            'code_link' => 'string',
            'demo_link' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_published' => 'int',
            'created_at' => 'date',
            'updated_at' => 'date',
            'budget' => 'float',
        ], $responseArray['availableResourceParameters']);
        $this->assertEquals([
            'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.',
            'index_url' => 'http://localhost:8000/api/v1/',
        ], $responseArray['info']);
        $this->assertArrayHasKey('acceptedParameters', $responseArray);
        $this->assertArrayHasKey('endpoint', $responseArray['acceptedParameters']);
        $this->assertEquals([
            'value' => $value,
            'message' => "This parameter's value dose not matter. If this parameter is set it will high jack the request and only return parameter data for this resource/endpoint",
        ], $responseArray['acceptedParameters']['columnData']);
        if ($url) {
            $this->assertArrayHasKey('id', $responseArray['acceptedParameters']);
        }
    }

    public static function columnProvider(): array
    {
        return [
            'columnData' => ['200', 'columnData', 'true'],
            'column_data' => ['200,300', 'column_data', '1'],
            'coLumndAtA' => ['200,300', 'coLumndAtA', 'no'],
            'columN_datA' => ['', 'columN_datA', ''],
        ];
    }

    /**
     * @dataProvider formDataProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_formData_returns_just_the_endpoints_forms_data(string $url, string $formData, string $value): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/{$url}/?{$formData}={$value}");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals([
            'id' => [
                'min' => 0,
                'max' => 1.8446744073709552E+19,
                'maxlength' => 20,
                'type' => 'number',
            ],
            'title' => [
                'min' => -128,
                'required' => true,
            ],
            'roles' => [
                'min' => -128,
            ],
            'client' => [
                'min' => -128,
            ],
            'description' => [
                'min' => -128,
            ],
            'content' => [
                'min' => -128,
            ],
            'video_link' => [
                'min' => -128,
            ],
            'code_link' => [
                'min' => -128,
            ],
            'demo_link' => [
                'min' => -128,
            ],
            'start_date' => [
                'type' => 'date',
                'min' => '1970-01-01 00:00:01',
                'max' => '2038-01-19 03:14:07',
            ],
            'end_date' => [
                'type' => 'date',
                'min' => '1970-01-01 00:00:01',
                'max' => '2038-01-19 03:14:07',
            ],
            'is_published' => [
                'min' => -128,
                'max' => 127,
                'maxlength' => 3,
                'type' => 'number',
            ],
            'created_at' => [
                'type' => 'date',
                'min' => '1970-01-01 00:00:01',
                'max' => '2038-01-19 03:14:07',
            ],
            'updated_at' => [
                'type' => 'date',
                'min' => '1970-01-01 00:00:01',
                'max' => '2038-01-19 03:14:07',
            ],
            'budget' => [
                'min' => -999999.99,
                'max' => '999999.99',
                'type' => 'number',
            ],
        ], $responseArray['formData']);
        $this->assertArrayHasKey('acceptedParameters', $responseArray);
        $this->assertArrayHasKey('endpoint', $responseArray['acceptedParameters']);
        $this->assertEquals([
            'value' => $value,
            'message' => "This parameter's value dose not matter. If this parameter is set it will high jack the request and only return parameter form data for this resource/endpoint",
        ], $responseArray['acceptedParameters']['formData']);
        if ($url) {
            $this->assertArrayHasKey('id', $responseArray['acceptedParameters']);
        }
    }

    public static function formDataProvider(): array
    {
        return [
            'formData' => ['200', 'formData', 'true'],
            'form_data' => ['200,300', 'form_data', '1'],
            'foRmData' => ['200,300', 'foRmData', 'no'],
            'fOrM_datA' => ['', 'fOrM_datA', ''],
        ];
    }

    /**
     * @dataProvider dataOnlyProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_dataOnly_returns_just_the_endpoints_data(string $url, string $pram, string $value): void
    {
        $this->createProjects();

        $response = $this->get("/api/v1/projects/{$url}/?{$pram}={$value}");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);

        $this->assertCount(2, $responseArray);
        $this->assertEquals('Test Project 1', $responseArray[0]['title']);
        $this->assertEquals('Test Project 2', $responseArray[1]['title']);
        $this->assertArrayNotHasKey('data', $responseArray);
        $this->assertArrayNotHasKey('acceptedParameters', $responseArray);
    }

    public static function dataOnlyProvider(): array
    {
        return [
            'dataOnly' => ['200,300', 'dataOnly', 'true'],
            'data_only' => ['200,300', 'data_only', '1'],
            'daTaOnLy' => ['200,300', 'daTaOnLy', 'no'],
            'dAtA_onlY' => ['200,300', 'dAtA_onlY', ''],
        ];
    }

    /**
     * @dataProvider fullInfoProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_fullInfo_returns_the_endpoints_full_info(string $url, string $pram, string $value): void
    {
        $this->createProjects();

        $response = $this->get("/api/v1/projects/{$url}/?{$pram}={$value}");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);

        $this->assertEquals('Test Project 1', $responseArray['data'][0]['title']);
        $this->assertEquals('Test Project 2', $responseArray['data'][1]['title']);
        $this->assertGreaterThan(4, $responseArray);
        $this->assertArrayHasKey('endpointData', $responseArray);
        $this->assertArrayHasKey('acceptedParameters', $responseArray);
        $this->assertArrayHasKey('fullInfo', $responseArray['acceptedParameters']);
        $this->assertArrayHasKey('rejectedParameters', $responseArray);
        $this->assertArrayHasKey('current_page', $responseArray);
    }

    public static function fullInfoProvider(): array
    {
        return [
            'fullInfo' => ['200,300', 'fullInfo', 'true'],
            'full_info' => ['200,300', 'full_info', '1'],
            'fuLlInfO' => ['200,300', 'fuLlInfO', 'false'], // false has no effect
            'fUlL_inFo' => ['200,300', 'fUlL_inFo', ''],
        ];
    }

    private function createProjects(): void
    {
        $this->project1 = Project::factory()->create([
            'id' => 200,
            'title' => 'Test Project 1',
        ]);
        $this->project2 = Project::factory()->create([
            'id' => 300,
            'title' => 'Test Project 2',
        ]);
        $this->project3 = Project::factory()->create([
            'title' => 'Test Project 3',
        ]);
        $this->project4 = Project::factory()->create([
            'title' => 'Test Project 4',
        ]);
        $this->project5 = Project::factory()->create([
            'title' => 'Test Project 5',
        ]);
    }
}
