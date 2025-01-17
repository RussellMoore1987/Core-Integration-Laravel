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
        'tests' => 'App\Models\Test',
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

    'defaultReturnRequestStructure' => 'dataOnly', // dev note: this effects lots of tests
    // default can only be one of the following:
        // fullInfo
        // dataOnly
    // parameter to effect request
        // dataOnly => return only the data requested
        // fullInfo => return all information for the resource
        // formData => return only the form data for the resource
        // columnData => return only the column data for the resource

    // TODO:
    // Available methods (ex: GET, POST) per endpoint
        // require API key for mutation requests
    // filtering (WHERE...) per endpoint
    // API keys, overarching, per endpoint
];
