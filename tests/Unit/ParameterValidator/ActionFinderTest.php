<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ActionFinder;
use Tests\TestCase;

class ActionFinderTest extends TestCase
{
    private $actionFinder;
    private $errorCollector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionFinder = new ActionFinder();
        $this->errorCollector = new ErrorCollector();
    }

    /**
     * @dataProvider parameterValueProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_ActionFinder_can_create_correct_responses($parameterValue, $expectedValue, $expectedComparisonOperator): void
    {
        [$value, $action, $originalComparisonOperator] = $this->actionFinder->parseValue($parameterValue, $this->errorCollector);

        $this->assertEquals($expectedValue, $value);
        $this->assertEquals($expectedComparisonOperator, $action);
        $this->assertEquals($expectedComparisonOperator, $originalComparisonOperator);
    }

    public function parameterValueProvider(): array
    {
        return [
            'oneValueOneAction' => ['123::gt', '123', 'gt'],
            'arrayValueOneActionWithSpaces' => [' 1,2,3 :: gt ', '1,2,3', 'gt'],
            'oneValueOneActionWithSpacesAndTabs' => ["\t123\t::\tgt\t", '123', 'gt'],
            'oneValueOneActionWithSpacesAndTabsAndNewLines' => ["\t123\t::\n\tgt\t\n", '123', 'gt'],
            'oneValueOneActionWithSpacesWithASingleQuote' => ["123::gt'", '123', "gt'"],
            'oneValueOneActionWithSpacesWithADoubleQuote' => ['123::gt"', '123', 'gt"'],
            'oneValueNoAction' => ['123', '123', null],
            'oneValueOnlyOneColonNoAction' => ['123:gt', '123:gt', null],
            'oneValueNoAction' => ['123::', '123', ''],
            'oneValueNoActionAndSpaces' => ['123  ::  ', '123', ''],
            'noValueOneAction' => ["::gt", '', 'gt'],
            'noValueNoAction' => ["::", '', ''],
            'noValueNoActionNoColons' => ["", '', ''],
        ];
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_ActionFinder_produces_appropriate_error(): void
    {
        $parameterValue = '123::gt::sam';
        [$value, $action, $originalComparisonOperator] = $this->actionFinder->parseValue($parameterValue, $this->errorCollector);

        $this->assertEquals('123', $value);
        $this->assertEquals('inconclusive', $action);
        $this->assertEquals([1 => "gt", 2 => "sam"], $originalComparisonOperator);
        $this->assertEquals([
            [
                'value' => $parameterValue,
                'valueError' => "Only one comparison operator is permitted per parameter, ex: 123::lt."
            ]
        ], $this->errorCollector->getErrors());
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_ActionFinder_can_create_correct_response_case_insensitive(): void
    {
        [$value, $action, $originalComparisonOperator] = $this->actionFinder->parseValue('sam::LiKE', $this->errorCollector);

        $this->assertEquals('sam', $value);
        $this->assertEquals('like', $action);
        $this->assertEquals('LiKE', $originalComparisonOperator);
    }
}
