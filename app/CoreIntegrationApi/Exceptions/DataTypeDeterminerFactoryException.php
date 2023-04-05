<?php

namespace App\CoreIntegrationApi\Exceptions;

use Exception;
use Throwable;

class DataTypeDeterminerFactoryException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}