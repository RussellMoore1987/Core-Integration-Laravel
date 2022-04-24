<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHistoryType extends Model
{
    use HasFactory;

    protected $primaryKey = 'work_history_type_id';
    public $formData = [
        'work_history_type_id' => [
            'min' => 1,
            'max' => 999999,
            'maxlength' => 6,
            'type' => 'number',
        ],
        
    ];

    public function workHistories()
    {
        return $this->hasMany(WorkHistory::class);
    }
}
