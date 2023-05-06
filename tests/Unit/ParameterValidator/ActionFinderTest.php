<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ActionFinder;
use Tests\TestCase;

// TODO: relook at this class

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
     * @dataProvider requestValueProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_ActionFinder_can_create_correct_responses($requestValue, $expectedValue, $expectedComparisonOperator): void
    {
        [$value, $action, $originalComparisonOperator] = $this->actionFinder->parseValue($requestValue, $this->errorCollector);

        $this->assertEquals($expectedValue, $value);
        $this->assertEquals($expectedComparisonOperator, $action);
        $this->assertEquals($expectedComparisonOperator, $originalComparisonOperator);
    }

    public function requestValueProvider(): array
    {
        return [
            'oneValueOneAction' => ['123::gt', '123', 'gt'],
            'arrayValueOneActionWithSpaces' => [' 1,2,3 :: gt ', '1,2,3', 'gt'],
            'oneValueOneActionWithSpacesAndTabs' => ["\t123\t::\tgt\t", '123', 'gt'],
            'oneValueOneActionWithSpacesAndTabsAndNewLines' => ["\t123\t::\n\tgt\t\n", '123', 'gt'],
            'oneValueOneActionWithSpacesWithASingleQuote' => ["123::gt'", '123', "gt'"],
            'oneValueOneActionWithSpacesWithADoubleQuote' => ['123::gt"', '123', 'gt"'],
            'oneValueOneActionWithSpacesWithASingleQuoteAndDoubleQuote' => ["123::gt'\"", '123', "gt'\""],
            'oneValueNoAction' => ['123', '123', null],
            'oneValueOnlyOneColonNoAction' => ['123:gt', '123:gt', null],
            'oneValueNoAction' => ['123::', '123', ''],
            'oneValueNoActionAndSpaces' => ['123  ::  ', '123', ''],
        ];
    }

    /**
     * @dataProvider requestValueErrorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_ActionFinder_produces_appropriate_errors($requestValue, $expectedValue, $expectedAction, $expectedComparisonOperator): void
    {
        [$value, $action, $originalComparisonOperator] = $this->actionFinder->parseValue($requestValue, $this->errorCollector);

        $this->assertEquals($expectedValue, $value);
        $this->assertEquals($expectedAction, $action);
        $this->assertEquals($expectedComparisonOperator, $originalComparisonOperator);
        $this->assertEquals([
            [
                'value' => $requestValue,
                'valueError' => "Only one comparison operator is permitted per parameter, ex: 123::lt."
            ]
        ], $this->errorCollector->getErrors());
    }

    public function requestValueErrorProvider(): array
    {
        return [
            'errorMultipleAction' => ['123::gt::sam', '123', 'inconclusive', [1 => "gt", 2 => "sam"]],
        ];
    }

    //  ! start here ************************************************************** am I testing everything?
    
}
