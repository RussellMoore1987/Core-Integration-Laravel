<?php

namespace App\Models;

use App\CoreIntegrationApi\CIL\CILModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use CILModel;

    protected $validationRules = [
        'updateValidation' => [],
        'createValidation' => [],
    ];
}
