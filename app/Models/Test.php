<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $primaryKey = 'test_id';

    public $availableMethodCalls = ['pluse1_5', 'votesTimeTwo', 'newName'];

    public function pluse1_5()
    {
        return 1 + 5;
    }

    public function votesTimeTwo()
    {
        return $this->votes * 2;
    }

    public function newName()
    {
        return $this->name . '!!!';
    }
}
