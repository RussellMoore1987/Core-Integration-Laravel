<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ContextApi\ContextRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

class ContextRequestDataPrepperTest extends TestCase
{
    protected $request;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = Request::create('api/v1/', 'POST');
    }

    public function test_context_request_data_prepper_returns_expected_result()
    {
        $this->request->request->add(['contextInstructions' => '{
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
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
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

    public function test_context_request_data_prepper_returns_expected_result_no_endpoint_id()
    {
        $this->request->request->add(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "tags": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP"
            }
        }']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "projects" =>[
                    "endpoint" => "projects",
                    "endpointId" => '',
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "tags" => [
                    "endpoint" => "tags",
                    "endpointId" => '',
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_endpoint_id()
    {
        $this->request->request->add(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "endpoint_id": 88
            },
            "tags": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP",
                "endpointId": 22
            }
        }']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "projects" =>[
                    "endpoint" => "projects",
                    "endpointId" => 88,
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "tags" => [
                    "endpoint" => "tags",
                    "endpointId" => 22,
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_default_numeric_result_name()
    {
        $this->request->request->add(['contextInstructions' => '[
            {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "endpoint_id": 88,
                "endpoint": "projects"
            },
            {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP",
                "endpointId": 22
            }
        ]']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "0" =>[
                    "endpoint" => "projects",
                    "endpointId" => 88,
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "1" => [
                    "endpoint" => "index",
                    "endpointId" => 22,
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_one_numeric_result_name()
    {
        $this->request->request->add(['contextInstructions' => '{
            "0": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "endpoint_id": 88,
                "endpoint": "projects"
            },
            "tags": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP",
                "endpointId": 22
            }
        }']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "0" =>[
                    "endpoint" => "projects",
                    "endpointId" => 88,
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "tags" => [
                    "endpoint" => "tags",
                    "endpointId" => 22,
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_result_name_different_the_endpoint()
    {
        $this->request->request->add(['contextInstructions' => '{
            "big_projects::projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "phpTags::tags": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP"
            }
        }']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "big_projects" =>[
                    "endpoint" => "projects",
                    "endpointId" => '',
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "phpTags" => [
                    "endpoint" => "tags",
                    "endpointId" => '',
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_result_name_with_many_options()
    {
        $this->request->request->add(['contextInstructions' => '{
            "big_projects::projects::444::big": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "phpTags::tags::::yuit::777": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP"
            }
        }']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "big_projects" =>[
                    "endpoint" => "projects",
                    "endpointId" => '',
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "phpTags" => [
                    "endpoint" => "tags",
                    "endpointId" => '',
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_unable_to_set_numeric_endpoint()
    {
        $this->request->request->add(['contextInstructions' => '{
            "big_projects::2": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "3": {
                "not_a_parameter": "hjkhjkhkjhkhkjhjk",
                "name": "PHP"
            }
        }']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => false,
            "contextMainError_instructions" => false,
            "requests" => [
                "big_projects" =>[
                    "endpoint" => "index",
                    "endpointId" => '',
                    "parameters" => [
                        "start_date" => "2020-02-28",
                        "title" => "Gogo!!!",
                    ]
                ],
                "3" => [
                    "endpoint" => "index",
                    "endpointId" => '',
                    "parameters" => [
                        "not_a_parameter" => "hjkhjkhkjhkhkjhjk",
                        "name" => "PHP",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_not_json_error()
    {
        $this->request->request->add(['contextInstructions' => 'not_json']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => true,
            "contextMainError_instructions" => false,
            "requests" => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_no_instructions()
    {
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            "contextErrorNotJson" => true,
            "contextMainError_instructions" => true,
            "requests" => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_not_json_error333()
    {
        $this->request->request->add(['contextInstructions' => '["not_json", "red", 44, 77]']);
        $this->contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $this->contextRequestDataPrepper->prep();
        $preppedData = $this->contextRequestDataPrepper->getPreppedData();
        dd($preppedData);

        $expectedResponse = [
            "contextErrorNotJson" => true,
            "contextMainError_instructions" => false,
            "requests" => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }
}
