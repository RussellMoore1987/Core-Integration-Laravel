<?php

namespace Tests\Integration;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// id are treated as integers but are utilized differently in the api, so they are tested separately

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

        $response = $this->get("/api/v1/projects/?{$perPageString}=2");

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
            'per_page' => ['per_page'],
        ];
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_page_default_pram_works_as_expected(): void
    {
        $this->createProjects();

        $response = $this->get('/api/v1/projects/?perPage=2&page=3');

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
                    'parameterError' => 'This parameter\'s value must be an int.',
                ],
                'perPage' => [
                    'value' => $perPageValue,
                    'parameterError' => 'This parameter\'s value must be an int.',
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

    public function test_page_or_per_page_does_not_affect_single_id_request(): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/{$this->project3->id}/?page=4&perPage=0");

        $response->assertStatus(200);
        $responseArray = json_decode($response->content(), true);
        $this->assertEquals($this->project3->id, $responseArray['id']);
        $this->assertEquals($this->project3->title, $responseArray['title']);

    }

    // ! start here ***************************************************************************** code review commits from 1e3e99e41241b6430c3611666e7d545267d26d3e to 490532c357a45c3e1b949dda888d8dd6b4959cef
    // page validation
    // per_page validation

    private function createProjects(): void
    {
        $this->project1 = Project::factory()->create([
            'title' => 'Test Project 1',
        ]);
        $this->project2 = Project::factory()->create([
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
