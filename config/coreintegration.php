<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Accepted Classes For the Core integration REST API
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
    ]

];
