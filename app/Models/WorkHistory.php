<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHistory extends Model
{
    protected $table = 'work_history';

    use HasFactory;

    public function workHistoryType()
    {
        return $this->belongsTo(WorkHistoryType::class);
    }
}
