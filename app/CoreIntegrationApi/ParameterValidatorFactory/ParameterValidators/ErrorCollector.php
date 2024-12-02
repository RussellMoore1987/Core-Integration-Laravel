<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

class ErrorCollector
{
    protected $errors = [];

    public function add(array $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
