<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class IntParameterDataProvider extends ParameterDataProvider
{
    protected $apiDataType = 'int';
    protected $intType;

    protected function getFormData()
    {
        $this->checkForTinyInt();
        $this->checkForSmallInt();
        $this->checkForMediumInt();
        $this->checkForInteger();
        $this->checkForBigInt();
        $this->checkForInt();
    }

    protected function checkForTinyInt()
    {
        if (!$this->intType && $this->isIntType('tinyint')) {
            
            $this->intType = true;

            $this->formData = [
                'min' => -128,
                'max' => 127,
                'maxlength' => 3,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 255;
            }

            $this->checkForClassFormData();
        }
    }

    protected function checkForSmallInt()
    {
        if (!$this->intType && $this->isIntType('smallint')) {
            
            $this->intType = true;

            $this->formData = [
                'min' => -32768,
                'max' => 32767,
                'maxlength' => 5,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 65535;
            }
        }
    }

    protected function checkForMediumInt()
    {
        if (!$this->intType && $this->isIntType('mediumint')) {
            
            $this->intType = true;

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
            }
        }
    }

    protected function checkForInteger()
    {
        if (!$this->intType && $this->isIntType('integer')) {
            
            $this->intType = true;

            $this->formData = [
                'min' => -2147483648,
                'max' => 2147483647,
                'maxlength' => 10,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 4294967295;
            }
        }
    }

    protected function checkForBigInt()
    {
        if (!$this->intType && $this->isIntType('bigint')) {
            
            $this->intType = true;

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
            }
        }
    }

    protected function checkForInt()
    {
        if (!$this->intType && $this->isIntType('int')) {
            
            $this->intType = true;

            $this->formData = [
                'min' => -2147483648,
                'max' => 2147483647,
                'maxlength' => 10,
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                $this->formData['min'] = 0;
                $this->formData['max'] = 4294967295;
            }
        }
    }

    protected function isIntType($intString)
    {
        if (str_contains($this->dataType, $intString)) {
            return true;
        }

        return false;
    }

    protected function isUnsignedInt()
    {
        if (str_contains($this->dataType, 'unsigned')) {
            return true;
        }

        return false;
    }
}