<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

// ? data type size https://dev.mysql.com/doc/refman/8.0/en/datetime.html
class DateResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'date';
    protected $dateType;

    protected function setParameterData(): void
    {
        $this->isDatetime();
        $this->isTimestamp();
        $this->isYear();
        $this->isDate();
    }

    protected function isDatetime(): void
    {
        if ($this->dateTypeIsNotSet() && $this->isDateType('datetime')) {
            
            $this->dateType = true;

            $this->defaultValidationRules = [
                'date',
                'after_or_equal:1000-01-01 00:00:00',
                'before_or_equal:9999-12-31 23:59:59',
            ];

            $this->formData = [
                'type' => 'date',
                'min' => '1000-01-01 00:00:00',
                'max' => '9999-12-31 23:59:59',
            ];
        }
    }

    protected function isTimestamp(): void
    {
        if ($this->dateTypeIsNotSet() && $this->isDateType('timestamp')) {
            
            $this->dateType = true;

            $this->defaultValidationRules = [
                'date',
                'after_or_equal:1970-01-01 00:00:01',
                'before_or_equal:2038-01-19 03:14:07',
            ];

            $this->formData = [
                'type' => 'date',
                'min' => '1970-01-01 00:00:01',
                'max' => '2038-01-19 03:14:07',
            ];
        }
    }

    protected function isYear(): void
    {
        if ($this->dateTypeIsNotSet() && $this->isDateType('year')) {
            
            $this->dateType = true;

            $this->defaultValidationRules = [
                'date',
                'after_or_equal:1901',
                'before_or_equal:2155',
            ];

            $this->formData = [
                'type' => 'date',
                'min' => '1901',
                'max' => '2155',
            ];
        }
    }

    protected function isDate(): void
    {
        if ($this->dateTypeIsNotSet() && $this->isDateType('date')) {
            
            $this->dateType = true;

            $this->defaultValidationRules = [
                'date',
                'after_or_equal:1000-01-01',
                'before_or_equal:9999-12-31',
            ];

            $this->formData = [
                'type' => 'date',
                'min' => '1000-01-01',
                'max' => '9999-12-31',
            ];
        }
    }

    protected function dateTypeIsNotSet(): bool
    {
        return !$this->dateType;
    }

    protected function isDateType($dateString): bool
    {
        return str_contains($this->parameterDataType, $dateString);
    }
}