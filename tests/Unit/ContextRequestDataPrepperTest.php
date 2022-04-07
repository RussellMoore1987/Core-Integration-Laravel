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
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "id": 33,
                "title": "Gogo!!!"
            },
            "tags": {
                "not_a_parameter": "Told You!",
                "id": 88,
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'projects' =>[
                    'endpoint' => 'projects',
                    'endpointId' => 33,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'endpoint' => 'tags',
                    'endpointId' => 88,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_no_endpoint_id()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "tags": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'projects' =>[
                    'endpoint' => 'projects',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'endpoint' => 'tags',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_endpoint_id()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "endpoint_id": 88
            },
            "tags": {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "endpointId": 22
            },
            "tags2": {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "id": 99
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'projects' =>[
                    'endpoint' => 'projects',
                    'endpointId' => 88,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'endpoint' => 'tags',
                    'endpointId' => 22,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ],
                'tags2' => [
                    'endpoint' => 'tags2',
                    'endpointId' => 99,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_default_numeric_result_name()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '[
            {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "endpoint_id": 88,
                "endpoint": "projects"
            },
            {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "endpointId": 22
            }
        ]']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                '0' => [
                    'endpoint' => 'projects',
                    'endpointId' => 88,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                '1' => [
                    'endpoint' => 1,
                    'endpointId' => 22,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_one_numeric_result_name()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "0": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "endpoint_id": 88,
                "endpoint": "projects"
            },
            "tags": {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "endpointId": 22
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                '0' =>[
                    'endpoint' => 'projects',
                    'endpointId' => 88,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'endpoint' => 'tags',
                    'endpointId' => 22,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_result_name_different_then_endpoint()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "big_projects::projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "phpTags::tags": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'big_projects' =>[
                    'endpoint' => 'projects',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'phpTags' => [
                    'endpoint' => 'tags',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_endpoint_name_order_is_correct()
    {
        // first two arrays will be combined 
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "big_projects::projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "big_projects": {
                "endpoint": "projects",
                "start_date": "2020-02-29",
                "title": "Gogo!!!"
            },
            "big_projects2::projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "big_projects3": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "phpTags::tags": {
                "endpoint": "pop",
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'big_projects' =>[
                    'endpoint' => 'projects',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-29',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'big_projects2' =>[
                    'endpoint' => 'projects',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'big_projects3' =>[
                    'endpoint' => 'big_projects3',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'phpTags' => [
                    'endpoint' => 'pop',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_result_name_with_many_options()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "big_projects::projects::444::big": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "phpTags::tags::::Gogo::777": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'big_projects' =>[
                    'endpoint' => 'projects',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'phpTags' => [
                    'endpoint' => 'tags',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_result_name_without_options()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "big_projects::": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "::": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            },
            ":": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            },
            "big_projects:projects": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'big_projects' =>[
                    'endpoint' => '',
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                '' => [
                    'endpoint' => '',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ],
                ':' => [
                    'endpoint' => ':',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ],
                'big_projects:projects' => [
                    'endpoint' => 'big_projects:projects',
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_set_numeric_endpoint()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "big_projects::2": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "3": {
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'big_projects' =>[
                    'endpoint' => 2,
                    'endpointId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                '3' => [
                    'endpoint' => 3,
                    'endpointId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_not_json_error()
    {
        $preppedData = $this->prepareData(['contextInstructions' => 'not_json']);

        $expectedResponse = [
            'contextErrorNotJson' => true,
            'contextErrorInstructions' => false,
            'requests' => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_empty_array()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '[]']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_empty_object()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{}']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_no_instructions()
    {
        $contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $contextRequestDataPrepper->prep();
        $preppedData = $contextRequestDataPrepper->getPreppedData();

        $expectedResponse = [
            'contextErrorNotJson' => true,
            'contextErrorInstructions' => true,
            'requests' => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_assorted_array_good_and_bad()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '["not_json", "red", 44, 77, {"endpoint": "projects", "name": "Sam", "id": 55}, {"endpoint": "tags", "id": 55},"", [], {}, true, false, null]']);

        $expectedResponse = [
            'contextErrorInstructions' => false,
            'contextErrorNotJson' => false,
            'requests' => [
                [
                    'endpoint' => 0,
                    'endpointId' => '',
                    'parameters' => 'not_json'
                ],
                [
                    'endpoint' => 1,
                    'endpointId' => '',
                    'parameters' => 'red'
                ],
                [
                    'endpoint' => 2,
                    'endpointId' => '',
                    'parameters' => 44
                ],
                [
                    'endpoint' => 3,
                    'endpointId' => '',
                    'parameters' => 77
                ],
                [
                    'endpoint' => 'projects',
                    'endpointId' => 55,
                    'parameters' => [
                      'name' => 'Sam'
                    ]
                ],
                [
                    'endpoint' => 'tags',
                    'endpointId' => 55,
                    'parameters' => []
                ],
                [
                    'endpoint' => 6,
                    'endpointId' => '',
                    'parameters' => ''
                ],
                [
                    'endpoint' => 7,
                    'endpointId' => '',
                    'parameters' => []
                ],
                [
                    'endpoint' => 8,
                    'endpointId' => '',
                    'parameters' => []
                ],
                [
                    'endpoint' => 9,
                    'endpointId' => '',
                    'parameters' => true
                ],
                [
                    'endpoint' => 10,
                    'endpointId' => '',
                    'parameters' => false
                ],
                [
                    'endpoint' => 11,
                    'endpointId' => '',
                    'parameters' => null
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    protected function prepareData($parameters)
    {
        $this->request->request->add($parameters);
        $contextRequestDataPrepper = new ContextRequestDataPrepper($this->request);
        $contextRequestDataPrepper->prep();
        $preppedData = $contextRequestDataPrepper->getPreppedData();

        return $preppedData;
    }
}
