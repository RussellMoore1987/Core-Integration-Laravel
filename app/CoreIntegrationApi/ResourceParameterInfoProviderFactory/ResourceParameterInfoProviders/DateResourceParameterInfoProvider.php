<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class DateResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'date';
    protected $dateType;

    protected function getParameterData()
    {
        $this->checkForDatetime();
        $this->checkForTimestamp();
        $this->checkForYear();
        $this->checkForDate();
    }

    protected function checkForDatetime()
    {
        if (!$this->dateType && $this->isDateType('datetime')) {
            
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

    protected function checkForTimestamp()
    {
        if (!$this->dateType && $this->isDateType('timestamp')) {
            
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

    protected function checkForYear()
    {
        if (!$this->dateType && $this->isDateType('year')) {
            
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

    protected function checkForDate()
    {
        if (!$this->dateType && $this->isDateType('date')) {
            
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

    protected function isDateType($dateString)
    {
        return str_contains($this->parameterDataType, $dateString) ? true : false;
    }
}