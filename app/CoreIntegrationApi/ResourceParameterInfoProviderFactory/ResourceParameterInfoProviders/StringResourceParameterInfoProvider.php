<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

// TODO: add string data types link
class StringResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'string';

    protected function setParameterData(): void
    {
        // is char
        // is varchar
        // is text

        // ! start here ********************************************************************

        dd(
            $this->parameterName,
            $this->parameterAttributeArray,
            $this->parameterDataType
        );

        $this->formData = ['min' => -128];
        $this->defaultValidationRules = ['min:-128'];
    }
}