<?php

namespace App\Models;

use App\CoreIntegrationApi\CIL\CILModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    use CILModel;

    public $availableMethodCalls = ['pluse1_5', 'budgetTimeTwo', 'newTitle'];

    public $availableIncludes = ['images', 'tags', 'categories'];

    public $formData = [
        'is_published' => [
            'min' => 0,
            'max' => 1,
            'maxlength' => 1,  
        ],
    ];

    protected $validationRules = [
        'updateValidation' => [
            'id' => [
                'integer',
                'min:1',
                'max:18446744073709551615',
            ],
            'title' => [
                'string',
                'max:75',
                'min:2',
            ],
            'roles' => [
                'string',
                'max:50',
            ],
            'client' => [
                'string',
                'max:50',
            ],
            'description' => [
                'string',
                'max:255',
                'min:10',
            ],
            'content' => [
                'string',
                'json',
            ],
            'video_link' => [
                'string',
                'max:255',
            ],
            'code_link' => [
                'string',
                'max:255',
            ],
            'demo_link' => [
                'string',
                'max:255',
            ],
            'start_date' => [
                'date',
            ],
            'end_date' => [
                'date',
            ],
            'is_published' => [
                'integer',
                'min:0',
                'max:1',
            ],
            'budget' => [
                'numeric',
                'max:999999.99',
                'min:0',
            ],
        ],
        'createValidation' => [
            'title' => [
                'required',
            ],
            'roles' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'start_date' => [
                'required',
            ],
            'budget' => [
                'required',
            ],

        ],
    ];

    public function images()
    {
        return $this->belongsToMany(Image::class)->withPivot('is_featured_img', 'sort_order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function setFeaturedImage(Image $image)
    {
        $this->unSetFeaturedImage();

        $this->images()->updateExistingPivot($image, [
                'is_featured_img' => 1,
                'sort_order' => 1
        ]);
        
        return true;
        // ex call $class->setFeaturedImage($class->images[0]);
    }

    public function unSetFeaturedImage()
    {
        // TODO: in the future just find the one featured image and unset it???
        $this->images()->updateExistingPivot($this->images, ['is_featured_img' => 0]);

        return true;
    }

    public function pluse1_5()
    {
        return 1 + 5;
    }

    public function budgetTimeTwo()
    {
        return $this->budget * 2;
    }

    public function newTitle()
    {
        return $this->title . '!!!';
    }
}
