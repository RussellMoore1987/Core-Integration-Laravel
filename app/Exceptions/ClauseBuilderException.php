<?php

namespace App\Exceptions;

use Exception;

class ClauseBuilderException extends Exception {

    public const COLUMN_NAME_NOT_SET_ERROR_MESSAGE = '"columnName" must be set in order to build the query!';
    public const STRING_NOT_SET_ERROR_MESSAGE = '"string" must be set in order to build the query!';
}
