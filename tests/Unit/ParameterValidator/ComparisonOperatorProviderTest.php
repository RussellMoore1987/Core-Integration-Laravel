<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ComparisonOperatorProvider;
use Tests\TestCase;

class ComparisonOperatorProviderTest extends TestCase
{
    protected $errorCollector;
    protected $comparisonOperatorProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->errorCollector = new ErrorCollector();
        $this->comparisonOperatorProvider = new ComparisonOperatorProvider();
    }

    /**
     * @dataProvider actionProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_selecting_the_correct_comparison_operator_using_default_acceptable_comparison_operators($action, $expectedComparisonOperator): void
    {
        $comparisonOperator = $this->comparisonOperatorProvider->select($action, $this->errorCollector);

        $this->assertEquals($expectedComparisonOperator, $comparisonOperator);
    }

    /**
     * @dataProvider actionProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_selecting_the_correct_comparison_operator_with_sub_set_of_acceptable_comparison_operators($action, $expectedComparisonOperator): void
    {
        $comparisonOperator = $this->comparisonOperatorProvider->select($action, $this->errorCollector, ['=', '<', $expectedComparisonOperator]);

        $this->assertEquals($expectedComparisonOperator, $comparisonOperator);
    }

    /**
     * @dataProvider actionProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_getting_an_error_for_invalid_action_based_on_comparison_operator_sub_set($action, $expectedComparisonOperator): void
    {
        $filterOptions = array_diff(['=','>','>=','<','<=','bt','in','notin'], [$expectedComparisonOperator]); // removes comparison operator in question

        $comparisonOperator = $this->comparisonOperatorProvider->select($action, $this->errorCollector, $filterOptions);

        $action = strtolower($action);
        $this->assertEquals('', $comparisonOperator);
        $this->assertEquals([[
            'value' => $action,
            'valueError' => "The comparison operator is invalid. The comparison operator of \"{$action}\" does not exist for this parameter.",
        ]], $this->errorCollector->getErrors());
    }

    public function actionProvider(): array
     {
        return [
            'equalsUsing_equal' => ['equal', '='],
            'equalsUsing__e' => ['e', '='],
            'equalsUsing_=' => ['=', '='],
            'greaterThanUsing_greaterThan' => ['greaterThan', '>'],
            'greaterThanUsing_gt' => ['gt', '>'],
            'greaterThanUsing_>' => ['>', '>'],
            'greaterThanOrEqualUsing_greaterThanOrEqual' => ['greaterThanOrEqual', '>='],
            'greaterThanOrEqualUsing_gt' => ['gte', '>='],
            'greaterThanOrEqualUsing_>=' => ['>=', '>='],
            'lessThanUsing_lessThan' => ['lessThan', '<'],
            'lessThanUsing_lt' => ['lt', '<'],
            'lessThanUsing_<' => ['<', '<'],
            'lessThanOrEqualUsing_lessThanOrEqual' => ['lessThanOrEqual', '<='],
            'lessThanOrEqualUsing_lte' => ['lte', '<='],
            'lessThanOrEqualUsing_<=' => ['<=', '<='],
            'in' => ['in', 'in'],
            'notInUsing_ni' => ['ni', 'notin'],
            'notInUsing_notIn' => ['notIn', 'notin'],
            'betweenUsing_between' => ['between', 'bt'],
            'betweenUsing_bt' => ['bt', 'bt'],
        ];
     }

     /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_we_get_an_error_when_selecting_an_invalid_parameter_using_default_acceptable_comparison_operators(): void
    {
        $comparisonOperator = $this->comparisonOperatorProvider->select('HAM', $this->errorCollector);

        $this->assertEquals('', $comparisonOperator);
        $this->assertEquals([[
            'value' => 'ham',
            'valueError' => 'The comparison operator is invalid. The comparison operator of "ham" does not exist for this parameter.',
        ]], $this->errorCollector->getErrors());
    }
}
