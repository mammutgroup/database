<?php

use Illuminate\Container\Container;
use Mammutgroup\Database\MysqlConnection;
use Mammutgroup\Database\Connectors\ConnectionFactory;

class ConnectionFactoryBaseTest extends BaseTestCase
{
    public function testMakeCallsCreateConnection()
    {
        $pgConfig = [ 'driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo' ];
        $pdo      = new DatabaseConnectionFactoryPDOStub;


        $factory = Mockery::mock(ConnectionFactory::class, [ new Container() ])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn    = $factory->createConnection('pgsql', $pdo, 'database', 'prefix', $pgConfig);

        $this->assertInstanceOf(MysqlConnection::class, $conn);
    }
}

class DatabaseConnectionFactoryPDOStub extends PDO
{
    public function __construct()
    {
    }
}
