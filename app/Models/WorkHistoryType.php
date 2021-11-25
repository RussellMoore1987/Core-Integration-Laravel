<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHistoryType extends Model
{
    use HasFactory;

    public function workHistories()
    {
        return $this->hasMany(WorkHistory::class);
    }
}
