<?php

namespace App\Models;

use App\CoreIntegrationApi\CIL\CILModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHistoryType extends Model
{
    use HasFactory;
    use CILModel;

    protected $primaryKey = 'work_history_type_id';

    public $formData = [
        'work_history_type_id' => [
            'min' => 1,
            'max' => 999999,
            'maxlength' => 6,
            'type' => 'number',
        ],
        
    ];

    protected $validationRules = [
        'updateValidation' => [
            'work_history_type_id' => [
                'integer',
                'min:1',
                'max:18446744073709551615',
            ],
            'name' => [
                'string',
                'max:35',
                'min:2',
            ],
            'icon' => [
                'string',
                'max:50',
                'min:2',
            ],
        ],
        'createValidation' => [
            'name' => [
                'required',
            ],
        ],
    ];


    public function workHistories()
    {
        return $this->hasMany(WorkHistory::class);
    }
}
