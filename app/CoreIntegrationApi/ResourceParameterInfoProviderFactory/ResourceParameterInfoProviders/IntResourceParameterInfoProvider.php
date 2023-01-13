<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

// ? https://dev.mysql.com/doc/refman/8.0/en/integer-types.html Data type details
class IntResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'int';
    protected $intType;
    protected $min0 = 'min:0';

    protected function getParameterData() : void
    {
        $this->isTinyInt();
        $this->isSmallInt();
        $this->isMediumInt();
        $this->isInteger();
        $this->isBigInt();
        $this->isInt();
    }

    protected function isTinyInt() : void
    {
        if ($this->intTypeIsNotSet() && $this->isIntType('tinyint')) {
            
            $this->intType = true;

            $this->defaultValidationRules = [
                'integer',
                'min:-128',
                'max:127',
            ];

            $this->formData = [
                'min' => -128,
                'max' => 127,
                'maxlength' => 3,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 255;

                $this->defaultValidationRules[1] = $this->min0;
                $this->defaultValidationRules[2] = 'max:255';
            }
        }
    }

    protected function isSmallInt() : void
    {
        if ($this->intTypeIsNotSet() && $this->isIntType('smallint')) {
            
            $this->intType = true;

            $this->defaultValidationRules = [
                'integer',
                'min:-32768',
                'max:32767',
            ];

            $this->formData = [
                'min' => -32768,
                'max' => 32767,
                'maxlength' => 5,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 65535;

                $this->defaultValidationRules[1] = $this->min0;
                $this->defaultValidationRules[2] = 'max:65535';
            }
        }
    }

    protected function isMediumInt() : void
    {
        if ($this->intTypeIsNotSet() && $this->isIntType('mediumint')) {
            
            $this->intType = true;

            $this->defaultValidationRules = [
                'integer',
                'min:-8388608',
                'max:8388607',
            ];

            $this->formData = [
                'min' => -8388608,
                'max' => 8388607,
                'maxlength' => 7,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 16777215;
                $this->formData['maxlength'] = 8;

                $this->defaultValidationRules[1] = $this->min0;
                $this->defaultValidationRules[2] = 'max:16777215';
            }
        }
    }

    protected function isInteger() : void
    {
        if ($this->intTypeIsNotSet() && $this->isIntType('integer')) {
            
            $this->intType = true;

            $this->defaultValidationRules = [
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ];

            $this->formData = [
                'min' => -2147483648,
                'max' => 2147483647,
                'maxlength' => 10,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 4294967295;

                $this->defaultValidationRules[1] = $this->min0;
                $this->defaultValidationRules[2] = 'max:4294967295';
            }
        }
    }

    protected function isBigInt() : void
    {
        if ($this->intTypeIsNotSet() && $this->isIntType('bigint')) {
            
            $this->intType = true;

            $this->defaultValidationRules = [
                'integer',
                'min:-9223372036854775808',
                'max:9223372036854775807',
            ];

            $this->formData = [
                'min' => -9223372036854775808,
                'max' => 9223372036854775807,
                'maxlength' => 19,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 18446744073709551615;
                $this->formData['maxlength'] = 20;

                $this->defaultValidationRules[1] = $this->min0;
                $this->defaultValidationRules[2] = 'max:18446744073709551615';
            }
        }
    }

    protected function isInt() : void
    {
        if ($this->intTypeIsNotSet() && $this->isIntType('int')) { // integer
            
            $this->intType = true;

            $this->defaultValidationRules = [
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ];

            $this->formData = [
                'min' => -2147483648,
                'max' => 2147483647,
                'maxlength' => 10,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 4294967295;

                $this->defaultValidationRules[1] = $this->min0;
                $this->defaultValidationRules[2] = 'max:4294967295';
            }
        }
    }

    protected function intTypeIsNotSet() : bool
    {
        return !$this->intType;
    }
    
    protected function isIntType($intString) : bool
    {
        return str_contains($this->parameterDataType, $intString) ? true : false;
    }
    
    protected function isUnsignedInt() : bool
    {
        return str_contains($this->parameterDataType, 'unsigned') ? true : false;
    }
}