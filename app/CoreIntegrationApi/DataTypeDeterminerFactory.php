<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\Exceptions\DataTypeDeterminerFactoryException;
use Illuminate\Support\Facades\App;

abstract class DataTypeDeterminerFactory
{
    protected $factoryItem;
    protected $dataType;
    // Just placeholder strings, should be replaced by paths to the actual classes, see app\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory.php for example
    protected $factoryItemArray = [
        'string' => 'string',
        'json' => 'json',
        'date' => 'date',
        'int' => 'int',
        'float' => 'float',
        'orderby' => 'orderby',
        'select' => 'select',
        'includes' => 'includes',
        'methodcalls' => 'methodcalls',
    ];

    public function getFactoryItem(string $dataType): object
    {
        $dataType = strtolower($dataType);
        $this->factoryItem = null; // rests if used more then once

        if (!array_key_exists($dataType, $this->factoryItemArray)) {
            throw new DataTypeDeterminerFactoryException("The dataType of \"{$dataType}\" is unsupported in the factoryItemArray, located in \"" . get_class($this) . '".');
        }
        
        return App::make($this->factoryItemArray[$dataType]);
    }
}
