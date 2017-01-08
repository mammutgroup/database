<?php

use Mammutgroup\Database\MysqlConnection;
use Mammutgroup\Database\Schema\Builder;
use Mammutgroup\Database\Schema\Blueprint;

class BuilderTest extends BaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = Mockery::mock(MysqlConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = Mockery::mock(Builder::class, [ $connection ]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {});

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
