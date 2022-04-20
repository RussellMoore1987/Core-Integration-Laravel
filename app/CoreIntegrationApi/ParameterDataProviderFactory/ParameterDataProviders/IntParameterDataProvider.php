<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class IntParameterDataProvider implements ParameterDataProvider
{
    protected $apiDataType = 'int';
    protected $formData = [];
    protected $intType;

    public function getData($dataType) : array
    {
        $this->dataType = strtolower($dataType);

        $this->getFormData();

        return [
            'apiDataType' => $this->apiDataType,
            'formData' => $this->formData,
        ];
    }

    protected function getFormData()
    {
        $this->checkForInteger();
        $this->checkForSmallint();
        $this->checkForInteger();
        $this->checkForInteger();
        $this->checkForInteger();
        $this->checkForInteger();
        $this->checkForInteger();
        if ($this->isIntType('integer')) {
            # code...
        } elseif ($this->isIntType('smallint')) {
            # code...
        } elseif ($this->isIntType('tinyint')) {
            # code...
        } elseif ($this->isIntType('mediumint')) {
            # code...
        } elseif ($this->isIntType('bigint')) {
            # code...
        } elseif ($this->isIntType('int')) {
            # code...
        }           
    }

    protected function checkForInteger()
    {
        if (!$this->intType && $this->isIntType('integer')) {
            
            $this->intType = 'integer';

            $this->formData = [
                'min' => '-128',
                'max' => '127',
                'maxCharacters' => '3',
                'type' => 'number',
            ];

            if ($this->isUnsignedInt()) {
                # code...
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



    // (
    //     (
    //         str_contains($this->dataType, $intString) && 
    //         str_contains($this->dataType, 'unsigned')
    //     ) || 
    //     str_contains($this->dataType, $intString)
    // )
}