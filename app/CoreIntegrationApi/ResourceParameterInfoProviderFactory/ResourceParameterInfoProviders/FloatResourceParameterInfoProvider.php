<?php

// TODO: Need to look over chat GPT created
// TODO: Also look at FloatResourceParameterInfoProviderTest.php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class FloatResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'float';
    protected $floatType;
    protected $floatParameterDataType;
    protected $precision;
    protected $scale;

    protected function setParameterData(): void
    {
        $this->prepareFloatParameterForProcessing();
        $this->isFloatThenSetParameterInfo();
        $this->isDoubleThenSetParameterInfo();
        $this->isDecimalThenSetParameterInfo();

        // dd(
        //     $this->floatParameterDataType,
        //     $this->parameterDataType,
        //     $this->precision,
        //     $this->scale,
        //     $this->defaultValidationRules,
        //     $this->formData
        // );
    }

    protected function prepareFloatParameterForProcessing(): void
    {
        // does it have a precision and scale?
        if (str_contains($this->parameterDataType, '(')) {
            $firstParen = strpos($this->parameterDataType, '(');
            $lastParen = strrpos($this->parameterDataType, ')');
            $precisionAndScale = substr($this->parameterDataType, $firstParen + 1, $lastParen - $firstParen - 1); // decimal(8,2) = 8,2
            $precisionAndScale = explode(',', $precisionAndScale);
            $this->precision = $precisionAndScale[0];
            $this->scale = $precisionAndScale[1] ?? 0;

            $this->floatParameterDataType = substr($this->parameterDataType, 0, $firstParen); // decimal(8,2) = decimal
        }

        $this->floatParameterDataType = $this->floatParameterDataType ?? $this->parameterDataType;
    }

    protected function isFloatThenSetParameterInfo(): void
    {
        if ($this->floatTypeIsNotSet() && $this->isFloatType('float')) {
            $this->setFloatTypeAsTrue();
            $this->setPrecisionAndScaleIfNotSet(24, 0);

            // TODO: make sure Laravel validation rules are correct
            $this->defaultValidationRules = [
                'numeric',
                'min:' . (-pow(10, $this->precision - $this->scale - 1)),
                'max:' . (pow(10, $this->precision - $this->scale) - pow(10, -$this->scale)),
            ];

            $this->formData = [
                'min' => (-pow(10, $this->precision - $this->scale - 1)),
                'max' => (pow(10, $this->precision - $this->scale) - pow(10, -$this->scale)),
                'type' => 'number',
            ];
        }
    }

    protected function isDoubleThenSetParameterInfo(): void
    {
        if ($this->floatTypeIsNotSet() && $this->isFloatType('double')) {
            $this->setFloatTypeAsTrue();
            $this->setPrecisionAndScaleIfNotSet(53, 0);

            $this->defaultValidationRules = [
                'numeric',
                'min:' . (-pow(10, $this->precision - $this->scale - 1)),
                'max:' . (pow(10, $this->precision - $this->scale) - pow(10, -$this->scale)),
            ];

            $this->formData = [
                'min' => (-pow(10, $this->precision - $this->scale - 1)),
                'max' => (pow(10, $this->precision - $this->scale) - pow(10, -$this->scale)),
                'type' => 'number',
            ];
        }
    }

    // https://dev.mysql.com/doc/refman/8.0/en/fixed-point-types.html
    protected function isDecimalThenSetParameterInfo(): void
    {
        if ($this->floatTypeIsNotSet() && $this->isFloatTypeDecimalOrNumeric()) {
            $this->setFloatTypeAsTrue();
            $this->setPrecisionAndScaleIfNotSet(10, 0);

            $precision = str_repeat('9', $this->precision - $this->scale);
            $scale = str_repeat('9', $this->scale);
            $decimal = $precision == '' ? '0' : $precision;
            if ($this->scale > 0) {
                $decimal .= '.' . $scale;
            }

            $min = str_contains($this->parameterDataType, 'unsigned') ? '0' : (-$decimal);

            $this->defaultValidationRules = [
                'numeric',
                'min:' . $min,
                'max:' . $decimal,
            ];

            $this->formData = [
                'min' => $min,
                'max' => $decimal,
                'type' => 'number',
            ];
        }
    }

    protected function isFloatTypeDecimalOrNumeric(): bool
    {
        return $this->isFloatType('decimal') || $this->isFloatType('numeric');
    }

    protected function floatTypeIsNotSet(): bool
    {
        return !$this->floatType;
    }

    protected function isFloatType($floatString): bool
    {
        return str_contains($this->parameterDataType, $floatString) ? true : false;
    }

    protected function setFloatTypeAsTrue(): void
    {
        $this->floatType = true;
    }

    protected function setPrecisionAndScaleIfNotSet($precision, $scale): void
    {
        $this->precision = $this->precision ?? $precision;
        $this->scale = $this->scale ?? $scale;
    }
}
