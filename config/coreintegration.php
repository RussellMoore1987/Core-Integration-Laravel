<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Accepted Classes For the Core integration API
    |--------------------------------------------------------------------------
    |
    | Specify the resource/endpoint and the corresponding model
    | 'resource/endpoint' => 'path\to\model'
    | ex: 'caseStudies' => 'App\Models\CaseStudy'
    |
    */

    'availableResourceEndpoints' => [
        'caseStudies' => 'App\Models\CaseStudy',
        'projects' => 'App\Models\Project',
        'content' => 'App\Models\Content',
        'experience' => 'App\Models\Experience',
        'images' => 'App\Models\Image',
        'posts' => 'App\Models\Post',
        'resources' => 'App\Models\Resource',
        'categories' => 'App\Models\Category',
        'tags' => 'App\Models\Tag',
        'skillTypes' => 'App\Models\SkillType',
        'skills' => 'App\Models\Skill',
        'workHistoryTypes' => 'App\Models\WorkHistoryType',
        'workHistory' => 'App\Models\WorkHistory',
        // 'tests' => 'App\Models\Test', // TODO broken because of time property
    ],

    'authenticationToken' => env('CORE_INTEGRATION_API_TOKEN', 'test'), // TODO: in production remove the default value 'test'

    // 'authenticationType' => 'Bearer', // formDataToken, Bearer, Bearer is default/preferred // TODO: test this

    'getProtected' => false, // effective for all endpoints, GET can only be protected if Bearer token is required, default true // TODO: test this

    // 'restrictedHttpMethods' => ['DELETE'], // effective for all endpoints // TODO: test this

    'routeOptions' => [ // TODO: test this
        'caseStudies' => [
            // token
            // sql restrictions
            // token sql restrictions
            // getProtected // GET can only be protected if Bearer token is required
            'authenticationToken' => env('CASE_STUDIES_API_TOKEN', 'test'),
            'restrictedHttpMethods' => ['POST', 'PUT', 'PATCH', 'DELETE'],
        ],
    ],

    // TODO: look into auto admin, genre of software that we are producing

    /*
    |--------------------------------------------------------------------------
    | Default Return Request Structure
    |--------------------------------------------------------------------------
    |
    | This allows you to specify what you would like the default structure of
    | requested data to be.
    |
    */

    'defaultReturnRequestStructure' => 'dataOnly', // @DefaultReturnRequestStructure
    // TODO: maybe add a trimmed pagination structure total, perPage, currentPage, lastPage, data

    // TODO:
    // Available methods (ex: GET, POST) per endpoint
        // require API key for mutation requests
    // filtering (WHERE...) per endpoint
    // API keys, overarching, per endpoint
];
