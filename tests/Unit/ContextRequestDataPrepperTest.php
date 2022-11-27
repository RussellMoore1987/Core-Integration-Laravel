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
                    'resource' => 'projects',
                    'resourceId' => 33,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'resource' => 'tags',
                    'resourceId' => 88,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_no_resource_id()
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
                    'resource' => 'projects',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'resource' => 'tags',
                    'resourceId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_different_ways_of_setting_resource_id()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "resource_id": 88
            },
            "tags": {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "resourceId": 22
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
                    'resource' => 'projects',
                    'resourceId' => 88,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'resource' => 'tags',
                    'resourceId' => 22,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ],
                'tags2' => [
                    'resource' => 'tags2',
                    'resourceId' => 99,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_default_numeric_request_names()
    {
        $preppedData = $this->prepareData(['contextInstructions' => '[
            {
                "start_date": "2020-02-28",
                "title": "Gogo!!!",
                "resource_id": 88,
                "resource": "projects"
            },
            {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "resourceId": 22
            }
        ]']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                '0' => [
                    'resource' => 'projects',
                    'resourceId' => 88,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                '1' => [
                    'resource' => 1,
                    'resourceId' => 22,
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
                "resource_id": 88,
                "resource": "projects"
            },
            "tags": {
                "not_a_parameter": "Told You!",
                "name": "PHP",
                "resourceId": 22
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                '0' =>[
                    'resource' => 'projects',
                    'resourceId' => 88,
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'tags' => [
                    'resource' => 'tags',
                    'resourceId' => 22,
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_request_names_different_then_endpoint()
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
                    'resource' => 'projects',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'phpTags' => [
                    'resource' => 'tags',
                    'resourceId' => '',
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
        // first two arrays will be combined, duplicated endpoint names will be overwritten 
        $preppedData = $this->prepareData(['contextInstructions' => '{
            "big_projects::projects": {
                "start_date": "2020-02-28",
                "title": "Gogo!!!"
            },
            "big_projects": {
                "resource": "projects",
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
                "resource": "pop",
                "not_a_parameter": "Told You!",
                "name": "PHP"
            }
        }']);

        $expectedResponse = [
            'contextErrorNotJson' => false,
            'contextErrorInstructions' => false,
            'requests' => [
                'big_projects' =>[
                    'resource' => 'projects',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-29',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'big_projects2' =>[
                    'resource' => 'projects',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'big_projects3' =>[
                    'resource' => 'big_projects3',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'phpTags' => [
                    'resource' => 'pop',
                    'resourceId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_request_names_sent_in_with_many_options()
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
                    'resource' => 'projects',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                'phpTags' => [
                    'resource' => 'tags',
                    'resourceId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_request_names_without_options_or_set_in_incorrectly()
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
                    'resource' => '',
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                '' => [
                    'resource' => '',
                    'resourceId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ],
                ':' => [
                    'resource' => ':',
                    'resourceId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ],
                'big_projects:projects' => [
                    'resource' => 'big_projects:projects',
                    'resourceId' => '',
                    'parameters' => [
                        'not_a_parameter' => 'Told You!',
                        'name' => 'PHP',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_set_numeric_endpoints()
    {
        // in the validator, these endpoints will be rejected
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
                    'resource' => 2,
                    'resourceId' => '',
                    'parameters' => [
                        'start_date' => '2020-02-28',
                        'title' => 'Gogo!!!',
                    ]
                ],
                '3' => [
                    'resource' => 3,
                    'resourceId' => '',
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

    public function test_context_request_data_prepper_returns_expected_result_no_context_instructions()
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
        $preppedData = $this->prepareData(['contextInstructions' => '["not_json", "red", 44, 77, {"resource": "projects", "name": "Sam", "id": 55}, {"resource": "tags", "id": 55},"", [], {}, true, false, null]']);

        $expectedResponse = [
            'contextErrorInstructions' => false,
            'contextErrorNotJson' => false,
            'requests' => [
                [
                    'resource' => 0,
                    'resourceId' => '',
                    'parameters' => 'not_json'
                ],
                [
                    'resource' => 1,
                    'resourceId' => '',
                    'parameters' => 'red'
                ],
                [
                    'resource' => 2,
                    'resourceId' => '',
                    'parameters' => 44
                ],
                [
                    'resource' => 3,
                    'resourceId' => '',
                    'parameters' => 77
                ],
                [
                    'resource' => 'projects',
                    'resourceId' => 55,
                    'parameters' => [
                      'name' => 'Sam'
                    ]
                ],
                [
                    'resource' => 'tags',
                    'resourceId' => 55,
                    'parameters' => []
                ],
                [
                    'resource' => 6,
                    'resourceId' => '',
                    'parameters' => ''
                ],
                [
                    'resource' => 7,
                    'resourceId' => '',
                    'parameters' => []
                ],
                [
                    'resource' => 8,
                    'resourceId' => '',
                    'parameters' => []
                ],
                [
                    'resource' => 9,
                    'resourceId' => '',
                    'parameters' => true
                ],
                [
                    'resource' => 10,
                    'resourceId' => '',
                    'parameters' => false
                ],
                [
                    'resource' => 11,
                    'resourceId' => '',
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