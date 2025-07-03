<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

// ? https://dev.mysql.com/doc/refman/8.0/en/string-types.html Data type details
class StringResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'string';
    protected $stringType;

    protected function setParameterData(): void
    {
        $this->isCharThenSetParameterInfo();
        $this->isVarcharThenSetParameterInfo();
        $this->isTinyTextThenSetParameterInfo();
        $this->isMediumTextThenSetParameterInfo();
        $this->isLongTextThenSetParameterInfo();
        $this->isTextThenSetParameterInfo(); // order matters
        $this->isEnumThenSetParameterInfo();
    }

    protected function isCharThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('char')) {
            
            $this->stringType = true;

            $maxLength = $this->extractLength() ?? 255;

            $this->defaultValidationRules = [
                'string',
                "max:{$maxLength}",
            ];

            $this->formData = [
                'maxlength' => $maxLength,
                'type' => 'text',
            ];
        }
    }

    protected function isVarcharThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('varchar')) {
            
            $this->stringType = true;

            $maxLength = $this->extractLength() ?? 255;

            $this->defaultValidationRules = [
                'string',
                "max:{$maxLength}",
            ];

            $this->formData = [
                'maxlength' => $maxLength,
                'type' => 'text',
            ];
        }
    }

    protected function isTinyTextThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('tinytext')) {
            
            $this->stringType = true;

            $this->defaultValidationRules = [
                'string',
                'max:255',
            ];

            $this->formData = [
                'maxlength' => 255,
                'type' => 'textarea',
            ];
        }
    }

    protected function isMediumTextThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('mediumtext')) {
            
            $this->stringType = true;

            $this->defaultValidationRules = [
                'string',
                'max:16777215',
            ];

            $this->formData = [
                'maxlength' => 16777215,
                'type' => 'textarea',
            ];
        }
    }

    protected function isLongTextThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('longtext')) {
            
            $this->stringType = true;

            $this->defaultValidationRules = [
                'string',
                'max:4294967295',
            ];

            $this->formData = [
                'maxlength' => 4294967295,
                'type' => 'textarea',
            ];
        }
    }

    protected function isTextThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('text')) {
            
            $this->stringType = true;

            $this->defaultValidationRules = [
                'string',
                'max:65535',
            ];

            $this->formData = [
                'maxlength' => 65535,
                'type' => 'textarea',
            ];
        }
    }

    protected function isEnumThenSetParameterInfo(): void
    {
        if ($this->stringTypeIsNotSet() && $this->isStringType('enum')) {
            
            $this->stringType = true;

            $options = $this->extractEnumOptions();

            $this->defaultValidationRules = [
                'string',
                'in:' . implode(',', $options),
            ];

            $this->formData = [
                'type' => 'select',
                'options' => $options,
            ];
        }
    }

    protected function stringTypeIsNotSet(): bool
    {
        return !$this->stringType;
    }
    
    protected function isStringType($stringType): bool
    {
        return str_contains($this->parameterDataType, $stringType) ? true : false;
    }

    protected function extractLength(): ?int
    {
        if (preg_match('/\((\d+)\)/', $this->parameterDataType, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    protected function extractEnumOptions(): array
    {
        if (preg_match('/enum\((.*?)\)/i', $this->parameterDataType, $matches)) {
            $optionsString = $matches[1];
            preg_match_all("/'([^']+)'/", $optionsString, $optionMatches);
            return $optionMatches[1];
        }
        return [];
    }
}
