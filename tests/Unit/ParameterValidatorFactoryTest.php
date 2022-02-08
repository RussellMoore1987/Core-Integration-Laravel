<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IdParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ParameterValidatorFactoryTest extends TestCase
{
    private $endpointData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ParameterValidatorFactory = new ParameterValidatorFactory();
    }

    // tests ------------------------------------------------------------
    /**
     * @dataProvider classDataProvider
     */
    public function test_making_class_returns_correct_instance_of_its_self($dataType, $classPath)
    {
        $newClass = $this->ParameterValidatorFactory->getParameterValidator($dataType);

        $this->assertInstanceOf($classPath, $newClass);
    }
    public function classDataProvider()
    {
        return [
            'StringParameterValidator' => ['varchar', StringParameterValidator::class],
            'DateParameterValidator' => ['varchar', DateParameterValidator::class],
            'IntParameterValidator' => ['varchar', IntParameterValidator::class],
            'FloatParameterValidator' => ['varchar', FloatParameterValidator::class],
            'IdParameterValidator' => ['varchar', IdParameterValidator::class],
            'OrderByParameterValidator' => ['varchar', OrderByParameterValidator::class],
            'SelectParameterValidator' => ['varchar', SelectParameterValidator::class],
            'IncludesParameterValidator' => ['varchar', IncludesParameterValidator::class],
            'MethodCallsParameterValidator' => ['varchar', MethodCallsParameterValidator::class],
        ];
    }









}
