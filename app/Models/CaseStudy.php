<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    use HasFactory;

    public function images()
    {
        return $this->belongsToMany(Image::class)->withPivot('is_featured_img', 'sort_order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function Categories()
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
}
