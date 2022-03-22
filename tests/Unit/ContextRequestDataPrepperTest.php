<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ContextApi\ContextRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

class ContextRequestDataPrepperTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $request = Request::create('/path/that/I_want', 'POST');
        $request->request->add(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "id": 33,
                "title": "Gogo!!!"
            },
            "tags": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "id": 88,
                "name": "PHP"
            }
        }']);

        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($request);
        
    }

    public function test_making_class_returns_correct_instance_of_its_self2()
    {
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextMainError" => false,
            "rejectedContextRequest" => [],
            "requests" => [
                "projects" =>[
                    "endpoint" => "projects",
                    "endpointId" => 33,
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "tags" => [
                    "endpoint" => "tags",
                    "endpointId" => 88,
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }
}
