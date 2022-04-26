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

    // TODO: apply sometimes validation rules to all updateValidation rules
    protected $validationRules = [
        'updateValidation' => [
            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'is_published' => [
                'sometimes',
                'integer',
                'min:0',
                'max:1',
            ],
        ],
        'createValidation' => [
            'name' => [
                'required',
            ],
            'description' => [
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
