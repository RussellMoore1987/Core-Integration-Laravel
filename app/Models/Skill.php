<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    public function skillType()
    {
        return $this->belongsTo(SkillType::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
