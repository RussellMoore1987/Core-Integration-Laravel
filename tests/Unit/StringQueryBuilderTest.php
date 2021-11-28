<?php

namespace Tests\Unit;

use App\QueryBuilders\StringQueryBuilder;

use Tests\TestCase;

class StringQueryBuilderTest extends TestCase
{
    private $queryBuilder;

    protected function setUp(): void
    {
        parent::setUp();


    }
    public function test_it_can_be_instanciated()
    {
        $queryBuilder = new StringQueryBuilder();

        $this->assertInstanceOf(StringQueryBuilder::class, $queryBuilder);
    }
}
