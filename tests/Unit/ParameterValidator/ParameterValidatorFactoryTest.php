<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\JsonParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use Tests\TestCase;

class ParameterValidatorFactoryTest extends TestCase
{
    /**
     * @dataProvider parameterValidatorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_creation_of_parameter_validator_classes($apiDataTyp, $expectedClass): void
    {
        $parameterValidatorFactory = new ParameterValidatorFactory();
        
        $parameterValidator = $parameterValidatorFactory->getFactoryItem($apiDataTyp);

        $this->assertInstanceOf($expectedClass, $parameterValidator);
    }

    public function parameterValidatorProvider(): array
    {
        return [
            'StringParameterValidator' => ['string', StringParameterValidator::class],
            'DateParameterValidator' => ['date', DateParameterValidator::class],
            'IntParameterValidator' => ['int', IntParameterValidator::class],
            'FloatParameterValidator' => ['float', FloatParameterValidator::class],
            'JsonParameterValidator' => ['json', JsonParameterValidator::class],
            'OrderByParameterValidator' => ['orderBy', OrderByParameterValidator::class],
            'SelectParameterValidator' => ['select', SelectParameterValidator::class],
            'IncludesParameterValidator' => ['includes', IncludesParameterValidator::class],
            'MethodCallsParameterValidator' => ['methodCalls', MethodCallsParameterValidator::class],
        ];
    }
}
